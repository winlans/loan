<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/21
 * Time: 16:27
 */
namespace User\Operator;

use App\Entity\Users;
use App\Operator\BaseOperator;
use App\Service\BaseValidator;
use App\Util\StringUtil;
use User\Repository\UsersRepository;
use Users\Operator\AuthCodeOperator;

class UserOperator extends BaseOperator
{
    public function register(array $data)
    {
        $fields = ['nickname', 'mobile', 'channel', 'package_name', 'log_ip', 'password', 'code'];
        $this->verifyInputParams($data, $fields);
        $data['password'] = StringUtil::encodePassword($data['password']);

        $authCode = new AuthCodeOperator($data);
        if ($authCode->isPhoneUsed())
            return $this->ensure(false, 23, 'phone has been used');

        /** @var UsersRepository $userRep */
        $userRep = $this->getRepository('User:Users');
        $userId = $userRep->add($data);

//        $authCode->verifyCode();
        return true;
    }

    public function resetPwd(array $data){
        /** @var BaseValidator $validator */
        $validator = $this->get('app.base_validator');
       if (false === $validator->phoneValidate($data['mobile']) )
           return $this->ensure(false, 34, 'mobile number format is error');

        /** @var UsersRepository $userRep */
        $userRep = $this->getRepository('User:Users');
        /** @var Users $user */
        $user = $userRep->findOneBy(['mobile' => $data['mobile']]);

        if( false == $user )
            return $this->ensure(false, 32, 'not found user by this phone number');
        $data['user_id'] = $user->getId();

        if ( !empty($data['code']) ) {
            if((new AuthCodeOperator($data))->verifyCode()){
                $user->setPassword(StringUtil::encodePassword($data['password']));
                $userRep->persist($user);
                $userRep->flush();
            }
        }

        return true;
    }

    public function getRegisterCode($data)
    {
        $data['user_id'] = $data['user_id'] ?? 0;

        $authCode = new AuthCodeOperator($data);
        if ( $authCode->isPhoneUsed() )
            return $this->ensure(false, 23, 'this phone has been register');

        if ( false === $authCode->getCodeAndNotify() )
            return $this->ensure(false, $authCode->getErrorCode(), $authCode->getEntityManager());

        return true;
    }
}