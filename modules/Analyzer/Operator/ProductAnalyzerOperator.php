<?php
/**
 * Created by PhpStorm.
 * User: winlans
 * Date: 2018/3/31
 * Time: 18:22
 */
namespace Analyzer\Operator;

class ProductAnalyzerOperator extends BaseAnalyzerOperator
{

    public function userEvent($data)
    {
        // 前端应该传递 点击的productId, 页面深度， userId
        // 使用有序集合存储用户的点击事件 ，
        // key 格式  日期_productId_userId_页面深度   socore存储用户的点击次数
        // 根据 日期_productId_userId_页面深度   的数量统计 product_uv ,  根据其值统计product_pv

        // 可以将每天的结果汇总存入mysql
        // productId, productName , pv(深度)， uv(深度)

    }

    public function actionRecord($data)
    {
        $redis = $this->getRedis();

        $redis->zIncrBy($this->getKey($data['product_id'], $data[' ']))
    }

}