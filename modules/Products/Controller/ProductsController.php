<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/21
 * Time: 11:15
 */
namespace Products\Controller;

use App\Controller\BaseController;
use App\HttpFoundation\JsonResponse;
use App\Service\File;
use Products\Operator\ProductsOperator;
use Symfony\Component\HttpFoundation\Request;

class ProductsController extends BaseController
{
    public function productAdd(Request $request){
        $data = $request->get('data');
        /** @var File $fileSer */
        $fileSer = $this->get('app.file');
        $files = $fileSer->upload($request->files->getIterator());
        $data['logo'] = current($files);

        $op = new ProductsOperator();
        $res = $op->add($data);
        if (false === $res){
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg());
        }
        return new JsonResponse(JsonResponse::STATUS_SUCCESS);
    }

    public function productModify(Request $request)
    {
        $data = $request->get('data');
        if ($request->files->getIterator()->current()){
            /** @var File $fileSer */
            $fileSer = $this->get('app.file');
            $files = $fileSer->upload($request->files->getIterator());
            $data['logo'] = current($files);
        }

        $op = new ProductsOperator();
        $res = $op->update($data);
        if (false === $res)
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg());

        return new JsonResponse(JsonResponse::STATUS_SUCCESS);
    }

    public function productDel(Request $request) {
        $id = $request->get('id');

        $op = new ProductsOperator();
        $res = $op->delete($id);
        if (false === $res)
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg(), $op->getErrorCode(true));

        return new JsonResponse(JsonResponse::STATUS_SUCCESS);
    }

    public function productList()
    {
        $op = new ProductsOperator();
        return new JsonResponse(JsonResponse::STATUS_SUCCESS, '', $op->listAll());
    }

    public function pagingShow(Request $request)
    {
        $data = json_decode($request->get('data'), true) ?? [];
        $op = new ProductsOperator();

        $res = $op->pagingList($data);
        if (false === $res)
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg(), '');

        return new JsonResponse(JsonResponse::STATUS_SUCCESS, '', $res);
    }

    public function productListForUser()
    {
        $op = new ProductsOperator();
        $filter = ['status', 'not_show', 'loan_period_max'];

        return new JsonResponse(JsonResponse::STATUS_SUCCESS, '', $op->listAll($filter));
    }
}