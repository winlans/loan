<?php
/**
 * Created by PhpStorm.
 * User: winlans
 * Date: 2018/3/31
 * Time: 18:16
 */

namespace App\Service;

use \Redis;

class RedisManager
{
    /** @var Redis */
    private $redis;

    private $currentDb;

    private $config;

    private $isConnected = false;

    public function __construct($config)
    {
        $this->config = $config;
        $this->currentDb = 0;
    }

    private function connect(){
        if($this->isConnected)
            return;

        $this->redis = new Redis();
        if(!$this->redis->pconnect($this->config['host'],
            $this->config['port'], $this->config['connect_timeout']))
            throw new \Exception('Redis server connect failed ');
        if(!$this->redis->auth($this->config['password']))
            throw new \Exception('Redis server authenticated failed.');

        $this->isConnected = true;
    }

    /**
     * @return Redis
     */
    public function getRedisClient()
    {
        $this->connect();
        return $this->redis;
    }

    public function expire($prefix, $key, $timeout){
        $this->connect();
        $key = $prefix . $key;
        return $this->redis->expire($key,(int)$timeout);
    }
    public function set($prefix, $key, $value, $timeout = 0){
        $this->connect();
        $key = $prefix . $key;
        return $timeout > 0 ?
            $this->redis->setex($key, (int)$timeout, $value) :
            $this->redis->set($key, $value);
    }

    public function get($prefix, $key, $default = null){
        $this->connect();
        $key = $prefix . $key;
        return $this->redis->exists($key) ? $this->redis->get($key) : ($default !== null ? $default : false);
    }

    public function delete($prefix, $key){
        $this->connect();
        $key = $prefix . $key;
        return $this->redis->del($key);
    }

    public function deleteAll($keys){
        $this->connect();
        return $this->redis->del($keys);
    }

    public function keys($pattern){
        $this->connect();
        return $this->redis->keys($pattern);
    }

    public function mSet($prefix, $keysValues){
        $this->connect();
        $withPrefix = array();
        foreach($keysValues as $key => $value)
            $withPrefix[$prefix . $key] = $value;
        return $this->redis->mset($withPrefix);
    }

    public function mGet($prefix, $keysValues){
        $this->connect();
        $withPrefix = array();
        foreach($keysValues as $key => $value)
            $withPrefix[$prefix . $key] = $value;
        return $this->redis->mget($keysValues);
    }

    public function exists($prefix, $key){
        $this->connect();
        $key = $prefix . $key;
        return $this->redis->exists($key);
    }

    public function lRange($key, $start=0, $stop=-1){
        $this->connect();
        return $this->redis->lRange($key, $start, $stop);
    }

    public function lPop($key){
        $this->connect();
        return $this->redis->lPop($key);
    }

    public function lRem($key, $data, $count){
        $this->connect();
        return $this->redis->lRem($key, $data, $count);
    }

    public function rpush($key, $value1, $value2 = null, $valueN = null ){
        $this->connect();
        return $this->redis->rPush($key, $value1, $value2, $valueN);
    }

    public function lpush($key, $value1){
        $this->connect();
        return $this->redis->lPush($key, $value1);
    }

    public function info(){
        $this->connect();
        return $this->redis->info();
    }

    public function setOption($name, $value){
        $this->connect();
        return $this->redis->setOption($name, $value);
    }

    /** @return Redis */
    public function getRedis(){
        $this->connect();
        return $this->redis;
    }

    /**
     * @param $key
     * @param $score1
     * @param $value1
     * @return int
     */
    public function zadd($key, $score1, $value1)
    {
        $this->connect();
        return $this->redis->zAdd($key, $score1, $value1);
    }

    /**
     * @param $key
     * @param $hashKey
     * @param $value
     * @return int
     */
    public function hset($key, $hashKey, $value)
    {
        $this->connect();
        return $this->redis->hSet($key, $hashKey, $value);
    }

    /**
     * @param $key
     * @param $hashKey
     * @return string
     */
    public function hget($key, $hashKey)
    {
        $this->connect();
        return $this->redis->hGet($key, $hashKey);
    }

    /**
     * @param $key
     * @param $hashKey
     * @return int
     */
    public function hdel( $key, $hashKey )
    {
        $this->connect();
        return $this->redis->hDel( $key, $hashKey );
    }

    /**
     * @param $key
     * @param $member
     * @return float
     */
    public function zscore($key, $member)
    {
        $this->connect();
        return $this->redis->zScore($key, $member);
    }

    /**
     * @param $key
     * @param $member
     * @return int
     */
    public function zrem( $key, $member )
    {
        $this->connect();
        return $this->redis->zRem( $key, $member );
    }

    /**
     * @param $key
     * @param $start
     * @param $end
     * @return array
     */
    public function zrangebyscore( $key, $start, $end )
    {
        $this->connect();
        return $this->redis->zRangeByScore( $key, $start, $end );
    }

    /**
     * @param $key
     * @param $start
     * @param $end
     * @return int
     */
    public function zremrangebyscore( $key, $start, $end )
    {
        $this->connect();
        return $this->redis->zRemRangeByScore( $key, $start, $end );
    }

    /**
     * @param $key
     * @param $value
     * @param $member
     * @return float
     */
    public function zIncrBy( $key, $value, $member )
    {
        $this->connect();
        return $this->redis->zIncrBy( $key, $value, $member );
    }

    /**
     * @param $key
     * @param $start
     * @param $end
     * @param null $withscores
     * @return array
     */
    public function zRange( $key, $start, $end, $withscores = null )
    {
        $this->connect();
        return $this->redis->zRange( $key, $start, $end, $withscores );
    }
}
