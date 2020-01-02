<?php


namespace App\Lib;


class Utils
{
    /**
     * Created by PhpStorm.
     * @Desc: 生成的唯一性key
     * @User: shenhengxin
     * @Date: 2020/1/2
     * @Time: 11:02
     * @param $str
     * @return bool|string
     */
    public static function getFileKey($str)
    {
        return substr(md5(self::makeRabdomString() . $str . time() . rand(0, 9999)),8,16);
    }

    /**
     * Created by PhpStorm.
     * @Desc: 生成随机字符串
     * @User: shenhengxin
     * @Date: 2020/1/2
     * @Time: 11:02
     * @param int $length
     * @return string|null
     */
    public static function makeRabdomString($length = 4)
    {
        $str = null;
        $strPol = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789";
        $max = strlen($strPol) - 1;

        for($i=0;$i<$length;$i++) {
            $str .= $strPol[rand(0,$max)];
        }
        return $str;

    }
}