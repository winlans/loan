<?php
namespace Users\Operator;


use App\Entity\AuthCode;
use App\Operator\BaseOperator;
use App\Service\BaseValidator;
use App\Service\AliSms\SendSms;
use User\Repository\AuthCodeRepository;
use User\Repository\UsersRepository;

class AuthCodeOperator extends BaseOperator
{
    /**
     * 1 - not logged in
     * 0 - logged in
     * @var int
     */
    const IS_ANONYMOUS = 1;
    const NOT_ANONYMOUS = 0;

    const TYPE_GENERAL = 1;
    const TYPE_REGISTER = 2;

    private $isAnonymous;

    private $code;
    private $type;

    public function __construct( $request )
    {
        $this->request = $request;
        $this->isAnonymous = self::NOT_ANONYMOUS;
    }

    private function notify()
    {
        header('Content-Type: text/plain; charset=utf-8');

        // send sms
        /**@var SendSms $sendSms*/
        $sendSms = $this->get('sms.send');
        $template = SendSms::TEMPLATE[$this->type];
        $response = (array)$sendSms->sendSms(SendSms::SIGN_NAME, $template, $this->request['mobile'], ["number"=>$this->code]);

        $this->getLogger()->addDebug(__METHOD__, array('code' => $response['Code'], 'message' => $response['Message']));
        if ($response['Code'] == 'OK')
            return true;

        return false;
    }

	/**
	 * get code & notify
	 * @param int $codeType
	 * @return bool|mixed
	 */
    public function getCodeAndNotify($codeType =self::TYPE_GENERAL)
    {
        $this->verifyInputParams($this->request, ['mobile', 'user_id']);
        if (empty($this->request['mobile']))
            return $this->ensure(false, 12, 'params can not be empty');

        /** @var BaseValidator $baseValidator */
        $baseValidator = $this->get('app.base_validator');

        if ( false == $baseValidator->phoneValidate($this->request['phone']))
            return $this->ensure(false, 13, 'phone format is error.');

        $this->type = $codeType;

        $this->generateCode();

        $this->request['code'] = $this->code;

        /** @var AuthCodeRepository $authCodeRep */
        $authCodeRep = $this->get('User:AuthCode');
        $authCodeRep->add($this->request);

        return $this->notify();
    }

    protected function generateCode()
    {
        $this->code = join('', array_map(function(){return mt_rand(0,9);}, range(1,6)));
    }

    /**
     * check if code is valid
     * @return bool
     */
    public function verifyCode()
    {
        /**@var  AuthCodeRepository $identificationCodeRep*/
        $identificationCodeRep = $this->getRepository( 'User:AuthCode' );
        $filter = [
            'code' => $this->request['code'],
            'status' => AuthCode::STATUS_VALID
        ];

        if (isset($this->request['code']))
            $filter['code'] = $this->request['code'];

        if (!$this->isAnonymous)
            $filter['userId'] = $this->request['user_id'];

        /**@var AuthCode  $identificationCode*/
        $identificationCode = $identificationCodeRep->findOneBy($filter);

        if ( false === $this->ensure( !empty( $identificationCode ), 1990008, 'Code expired or does not exist.' ) )
            return false;

        $isExpired = 0;

        if ( false === $this->ensure(
            $identificationCode->getCreated()->getTimestamp() + AuthCode::TTL >= time(),
            1990009, 'Code has been expired.' ) )
            $isExpired = 1;

        // update code
        $identificationCode->setStatus( AuthCode::STATUS_INVALID );
        $identificationCodeRep->flush();

        if ( $isExpired == 1 )
            return false;

        return true;
    }

    /**
     * @param int $isAnonymous
     */
    public function setAnonymous($isAnonymous = self::NOT_ANONYMOUS)
    {
        $this->isAnonymous = $isAnonymous;
    }

    /**
     * checks if phone has been used
     * mainly for register
     * @return bool
     */
    public function isPhoneUsed()
    {
        /**@var UsersRepository $userRep*/
        $userRep = $this->getRepository('User:Users');
        $user = $userRep->findOneBy(['mobile'=>$this->request['means']]);

        if (false === $this->ensure(empty($user), 1010005, 'This phone has been used'))
            return true;

        return false;
    }

}