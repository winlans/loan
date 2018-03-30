<?php

namespace App\HttpFoundation;

use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;

class JsonResponse extends SymfonyJsonResponse
{

    const STATUS_FAILED = 0;
    const STATUS_SUCCESS = 1;


    /**
     * 内部交互
     */
    const STATUS_INSIDE_PARAM_INVALID = 10001; // Invalid parameters
    const STATUS_INSIDE_PARAM_MISSED = 10002; // Missing parameters

    const STATUS_LOGIN_REQUIRED = -10001;
    const STATUS_LOGOUT_REQUIRED = -10002;

    const STATUS_ONLY_ADMIN_PERMISSION = 10004;

    public function __construct($status, $message = null, $data = null, $httpStatus = 200, $headers = array())
    {
        $reject = [
            'meta' => [
                'success' => $status,
                'status' => $status,
                'messageCode' => $message
            ],
            'data' => $data
        ];
        parent::__construct(array(
            'status' => $status,
            'message' => $message,
            'data' => $data
        ), $httpStatus, $headers);

        empty($data) && $this->setEncodingOptions(JSON_FORCE_OBJECT);
    }

}