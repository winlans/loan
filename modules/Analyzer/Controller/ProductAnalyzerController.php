<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/4/2
 * Time: 10:16
 */
namespace Analyzer\Controller;

use Analyzer\Operator\ProductAnalyzerOperator;
use Analyzer\Repository\AccessRecordRepository;
use App\Controller\BaseController;
use App\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductAnalyzerController extends BaseController
{
    /**
     *生成用于测试的访问记录
     */
    public function testRecord(){
        /** @var AccessRecordRepository $rep */
        set_time_limit(0);
        $rep = $this->getRepository('Analyzer:AccessRecord');

        $rep->testRecord();
        return new JsonResponse(JsonResponse::STATUS_SUCCESS, 'OK');
    }

    public function getProductStatistics(Request $request)
    {
        $data = json_decode($request->get('data'), true) ?? [];

        $op = new ProductAnalyzerOperator();
        $res = $op->statisticsProduct($data);

        return new JsonResponse(JsonResponse::STATUS_SUCCESS, '', $res);
    }

    public function accessLogAction(Request $request)
    {
        $data = json_decode($request->get('data'), true) ?? [];

        $op = new ProductAnalyzerOperator();
        $res = $op->updateRecord($data);
        if (false === $res)
            return new JsonResponse(JsonResponse::STATUS_FAILED, $op->getErrorMsg());

        return new JsonResponse(JsonResponse::STATUS_SUCCESS);
    }
}