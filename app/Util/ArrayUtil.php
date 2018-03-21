<?php
/**
 * Created by PhpStorm.
 * User: Spectre
 * Date: 2016/4/8
 * Time: 15:11
 */

namespace App\Util;


class ArrayUtil
{
    /**
     * @param array $param
     * @return array
     */
    public static function toJsonArray($param){
        $companyConst = array();
        foreach($param as $key => $value){
            $companyConst[] = array(
                'id' => (string)$key,
                'name' => $value,
            );
        }
        return $companyConst;
    }

    /**
     * 返回指定区间的日期
     * 输入 2017-12-04  2107-12-07  输出  2017-12-04 2017-12-05 2017-12-06 2017-12-07
     * @param $begDate
     * @param $endDate
     * @return array
     */
    public static function intervalDate($begDate,$endDate)
    {
        $date = array();
        $begTime = strtotime($begDate)+86400;
        $endTime = strtotime($endDate);
        while($begTime < $endTime)
        {
            $date[]= date("Y-n-d",$begTime);
            $begTime+=86400;
        }
        return $date;
    }

    /**
     * 合并二维数组到一维数组
     * 输入：[[1,2,3], [4,5,6]] 输出：[1,2,3,4,5,6]
     * @param $arr
     * @return array $arr
     */
    public static function reduceArr($arr){
        if ( !is_array(current($arr)) ){
            return $arr;
        }
        return array_reduce($arr, function ($carry, $item){
            return array_merge(array_values($item), (array)$carry);
        });
    }

    /**
     * 消除数组中的指定字段, 一维或者二维
     * @param $arr
     * @param $discardField
     * @param $D  // 数组的维度
     */
    public static function filterField(&$arr, $discardField, $D = 1){
        if ( $D == 1 ) {
            foreach ($discardField as $discard){
                unset($arr[$discard]);
            }
        }else{
            foreach ($discardField as $discard) {
                foreach ($arr as &$v) {
                    unset($v[$discard]);
                }
            }
            unset($v);
        }
    }
}