<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/21
 * Time: 12:59
 */
namespace App\Traits;
use App\HttpFoundation\JsonResponse;

trait ErrorInfo{
    private $error;

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

}