<?php
/**
 * Created by PhpStorm.
 * User: winlans
 * Date: 2018/3/31
 * Time: 18:39
 */
namespace Analyzer\Operator;
use App\Operator\BaseOperator;
use App\Service\RedisManager;

class BaseAnalyzerOperator extends BaseOperator
{

    /**
     * @return RedisManager
     */
    public function getRedis(){
        /** @var RedisManager $redis */
        return $this->get('app.redis_manager');
    }

    protected function getKey($productId, $deepth)
    {
        $dateStr = (new \DateTime())->format('Ymd');
        $userId = $this->getUser('id');

        return $dateStr . $productId . $userId . $deepth;
    }
}