<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 11:05
 */

namespace App\Util;

class StringUtil{

    public static function camelToUnderscore($str){
        $length = mb_strlen($str);
        $new = '';
        for($i = 0; $i < $length; $i++)
        {
            $num = ord($str[$i]);
            $pre = $i > 0 ? ord($str[$i - 1]) : null;
            $new .= ($i > 0 && ($num >= 65 && $num <= 90) && ($pre >= 97 && $pre <= 122)) ? "_{$str[$i]}" : $str[$i];
        }

        return strtolower($new);
    }


    /**
     * 密码加密器
     * @param $plainPassword
     * @return bool|string
     */
    public static  function encodePassword($plainPassword)
    {
        $options = [
            'cost' => 13
        ];
        return password_hash($plainPassword, PASSWORD_BCRYPT, $options);
    }

    /**
     * 密码验证
     * @param $password
     * @param $hash
     * @return bool
     */
    public static function passwordVerify($password, $hash)
    {
        return password_verify($password, $hash);
    }

}