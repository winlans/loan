<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/21
 * Time: 10:42
 */
namespace App\Exception;

use App\HttpFoundation\JsonResponse;
use Throwable;

class MissParamException extends \Exception {
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
       $this->code = JsonResponse::STATUS_INSIDE_PARAM_MISSED;
       $this->message = $message;
    }
}