<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 11:07
 */

namespace App\Operator;

use App\Application;
use App\Doctrine\ORM\EntityManager;
use App\Service\BaseValidator;
use App\Service\EmFactory;
use App\Service\Security;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\HttpFoundation\Session\Session;
use App\HttpFoundation\JsonResponse;

abstract class BaseOperator{
    /** @var  ContainerBuilder */
    private $container;

    /** @var  EntityManager */
    private $entityManger;

    /** @var Logger */
    protected $logger;

    protected $request;

    //错误信息
    protected $error;



    const VALID = 1;   // 有效
    const INVALID = 0; // 无效

    /**
     * Gets a container service by its id.
     * @param string $id The service id
     * @return object The service
     */
    protected function get($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Gets a container.
     */
    protected function getContainer()
    {
        if (!$this->container) {
            $this->container = Application::$_singleton['container'];
        }
        return $this->container;
    }

    /**
     * @param $moduleAndEntity
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository($moduleAndEntity)
    {
        return $this->getEntityManager()->getRepository($moduleAndEntity);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        if (!$this->entityManger) {
            /** @var EmFactory $biEmFactory */
            $biEmFactory = $this->get('app.em_factory');
            $this->entityManger = $biEmFactory->getEntityManager();
        }
        return $this->entityManger;
    }

    /**
     * Get logger instance
     * @param $channel
     * @return \Monolog\Logger
     */
    public function getLogger($channel='api.base') {
        if (!($this->logger instanceof Logger)) {
            $this->logger = new Logger('Detection');
            $file = __DIR__ . DIRECTORY_SEPARATOR . '../../logs/'. $channel .'.log';
            $this->logger->pushHandler(new StreamHandler($file, logger::DEBUG));
        }
        return $this->logger;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->get('session');
    }

    /**
     * @param $data
     * @param $fields
     * @return bool
     */
    public function checkIsExistField($data, $fields)
    {
        $param = $this->verifyInputParams($data, $fields);

        if ($param['status'] != 1) {
            return $this->ensure(false, JsonResponse::STATUS_INSIDE_PARAM_MISSED, $param['msg']);
        }
        return true;
    }

    /**
     * 判断字段是不是存在，不存在，返回错误，存在，返回指定字段的值
     * @param $data
     * @param $fields
     * @return array
     */
    public function verifyInputParams($data, $fields){
        /** @var BaseValidator $baseValidator */
        $baseValidator = $this->get('app.base_validator');
        return $baseValidator->verifyInputParams($data, $fields);
    }

    /**
     * 获取错误数据
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 获取错误码
     * @param $custom bool 是否返回 $capture 之外的状态码
     * @return  int
     */
    public function getErrorCode($custom = false)
    {
        if ( $custom ) {
            return $this->error['errorCode'];
        }

        $capture = [];
        if (in_array($this->error['errorCode'], $capture)) {
            return $this->error['errorCode'];
        }
        return JsonResponse::STATUS_FAILED;
    }

    /**
     * 获取错误消息
     * @return string
     */
    public function getErrorMsg(){
        return $this->error['errorMsg'] ? : '';
    }

    /**
     * fixme: integrate EnsureService
     * @param $exp
     * @param $errorCode
     * @param $errorMsg
     * @return bool
     */
    protected function ensure( $exp, $errorCode, $errorMsg )
    {
        if ( !$exp ) {
            $this->error = array(
                'errorCode' => $errorCode,
                'errorMsg' => $errorMsg
            );

            return false;
        }

        return true;
    }


    /**
     * 由于当时间为 "0000-00-00 00:00:00" 时，strtotime 返回为负值，
     * @param $time
     * @return false|int|string
     */
    protected function _getTimestamp($time){
        $timestamp = strtotime($time);
        if ($timestamp === false || $timestamp <= 0){
            return '';
        }
        return $timestamp;
    }
}