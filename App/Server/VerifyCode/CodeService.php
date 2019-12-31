<?php


namespace App\Server\VerifyCode;


use EasySwoole\Pool\Manager;
use EasySwoole\VerifyCode\Conf;
use EasySwoole\VerifyCode\VerifyCode;

class CodeService
{
    public static function getCode($key)
    {
        $config = new Conf();
        $code = new VerifyCode($config);
        $code_obj = $code->DrawCode();
        $obj = Manager::getInstance()->get('redis')->getObj();
        $get_code = $code_obj->getImageCode();
        $obj->set($get_code."_".$key,$get_code,300);
        Manager::getInstance()->get('redis')->recycleObj($obj);
        return $code_obj;
    }
}