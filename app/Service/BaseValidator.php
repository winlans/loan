<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 13:40
 */
namespace App\Service;

use App\Exception\MissParamException;
use App\HttpFoundation\JsonResponse;

class BaseValidator{

    /**
     * @param string $email
     * @return bool
     */
    function emailValidate($email){
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param string $phone
     * @return bool
     */
    function phoneValidate($phone){
        $pattern = "/^1[34578]\\d{9}$/";
        return (bool)preg_match($pattern, $phone);
    }


    /**
     * @param $time
     * @return \DateTime
     */
    function timeProcessing($time) {
        if (empty($time)) {
            return new \DateTime('0000-00-00 00:00:00');
        }

        if(strtotime($time)) {
            return new \DateTime($time);
        } else {
            return new \DateTime(date ('Y-m-d H:i:s', $time));
        }
    }

    /**
     * Check whether the field exists, there is a return value, there is no return error information
     * 检查字段是否存在，存在返回数值，不存在返回错误信息
     * @param $data
     * @param $fields
     * @return array
     */
    function verifyInputParams($data, $fields) {
        $param = array();
        foreach ($fields as $key => $val) {
            if (!isset($data[$val])) {
                throw new MissParamException('Illegal operation not find ' . $val . ' field', JsonResponse::STATUS_INSIDE_PARAM_MISSED);
            }

            $param[$key] = $data[$val];
        }

        return $param;
    }

}
