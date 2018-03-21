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
    public function register(Request $request){

    }

    public function getRegisterCode(Request $request){
        $data = json_decode($request->get('data'), true);

        $userOpt = new UserOperator();
        if (false === $userOpt->getRegisterCode($data) )
            return new JsonResponse(JsonResponse::STATUS_FAILED, $userOpt->getErrorMsg());

        return new JsonResponse(JsonResponse::STATUS_SUCCESS);
    }
}