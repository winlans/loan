<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/21
 * Time: 15:32
 */

namespace User\Controller;

use App\Controller\BaseController;
use App\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use User\Operator\UserOperator;

class UserController extends BaseController
{
    public function register(Request $request)
    {
        $data = json_decode($request->get('data'));
        $op = new UserOperator();
        $res = $op->register($data);

        if ($res === false)
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg());

        return new JsonResponse(JsonResponse::STATUS_SUCCESS);
    }

    public function getRegisterCode(Request $request)
    {
        $data = json_decode($request->get('data'), true);

        $userOpt = new UserOperator();
        if (false === $userOpt->getRegisterCode($data))
            return new JsonResponse(JsonResponse::STATUS_FAILED, $userOpt->getErrorMsg());

        return new JsonResponse(JsonResponse::STATUS_SUCCESS);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function loginAction(Request $request)
    {
        $data = (array)json_decode($request->get('data', []), true);

        $op = new UserOperator();
        $userinfo = $op->login($data);

        if (false === $userinfo)
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg());

        return new JsonResponse(JsonResponse::STATUS_SUCCESS, 'success', $userinfo);
    }
}