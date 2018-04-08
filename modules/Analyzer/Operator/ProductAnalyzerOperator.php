<?php
/**
 * Created by PhpStorm.
 * User: winlans
 * Date: 2018/3/31
 * Time: 18:22
 */
namespace Analyzer\Operator;
use Analyzer\Repository\AccessRecordRepository;

class ProductAnalyzerOperator extends BaseAnalyzerOperator
{
    const MODEL_BY_DAY = 1;
    const MODEL_BY_DAY_PRODUCT = 2;

    public function userEvent($data)
    {
        // 前端应该传递 点击的productId, 页面深度， userId
        // 使用有序集合存储用户的点击事件 ，
        // key 格式  日期_productId_userId_页面深度   socore存储用户的点击次数
        // 根据 日期_productId_userId_页面深度   的数量统计 product_uv ,  根据其值统计product_pv

        // 可以将每天的结果汇总存入mysql
        // productId, productName , pv(深度)， uv(深度)
    }

    public function updateRecord($data)
    {
        //deepth 访问的深度
        $fileds = ['product_id', 'product_name', 'deepth'];
        $this->verifyInputParams($data, $fileds);

        if (false === $this->ensure($this->productExist($data['product_id']), 32, 'not found product by this id'))
            return false;

        $data['user_id'] = $this->getUser('id');
        $data['created'] = (new \DateTime())->format('Y-m-d');

        /** @var AccessRecordRepository $rep */
        $rep = $this->getRepository('Analyzer:AccessRecord');
        $rep->update($data);

        return true;
    }

    public function statisticsProduct($data)
    {
        $this->verifyInputParams($data, ['model', 'page']);

        $data['page'] = $data['page'] < 1 ? 1 : $data['page'];
        $data['size'] = $data['size'] ?? 20;

        $offset = ($data['page']  - 1) * $data['size'];

        /** @var AccessRecordRepository $rep */
        $rep = $this->getRepository('Analyzer:AccessRecord');

        if ($data['model'] == self::MODEL_BY_DAY_PRODUCT)
            return $rep->fetchAccessByDayAndProduct($offset, $data['size']);

        return $rep->fetchAccessByDay($offset, $data['size']);
    }

    private function productExist($id)
    {
        return (boolean)($this->getRepository('Products:Products'))->find($id);
    }
}