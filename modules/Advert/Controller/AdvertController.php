<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/30
 * Time: 15:03
 */
namespace Advert\Controller;

use Advert\Operator\AdvertOperator;
use App\Controller\BaseController;
use App\HttpFoundation\JsonResponse;
use App\Service\File;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class AdvertController extends BaseController
{
    public function add(Request $request)
    {
        $data = json_decode($request->get('data'), true);
        if ($request->files->getIterator()->current()){
            /** @var File $fileSer */
            $fileSer = $this->get('app.file');
            $files = $fileSer->upload($request->files->getIterator());
            $data['logo_path'] = current($files);
        }
        $op = new AdvertOperator();
        $res = $op->add($data);
        if (false === $res)
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg());

        return new JsonResponse(JsonResponse::STATUS_SUCCESS, 'success', $res);
    }

    public function delete(Request $request){
        $id = (int)$request->get('id');

        $op = new AdvertOperator();
        $res = $op->delete($id);
        if (false === $res)
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg());

        return new JsonResponse(JsonResponse::STATUS_SUCCESS, 'success', $res);
    }

    public function update(Request $request) {
        $data = json_decode($request->get('data'), true);

        if ($request->files->getIterator()->current()){
            /** @var File $fileSer */
            $fileSer = $this->get('app.file');
            $files = $fileSer->upload($request->files->getIterator());
            $data['logo_path'] = current($files);
        }

        $op = new AdvertOperator();
        $res = $op->update($data);
        if (false === $res)
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg());

        return new JsonResponse(JsonResponse::STATUS_SUCCESS, 'success', $res);
    }

    public function pagingShow(Request $request)
    {
        $data = json_decode($request->get('data'), true) ?? [];

        $op = new AdvertOperator();

        return new JsonResponse(JsonResponse::STATUS_SUCCESS, 'success', $op->paging($data));
    }

}