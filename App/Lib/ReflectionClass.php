<?php


namespace App\Lib;


use EasySwoole\Http\Exception\Exception;

class ReflectionClass
{
    public function UploadClassStat()
    {
        return [
            "image" => "\App\Server\Upload\Image",
            "video" => "\App\Server\Upload\Video",
        ];
    }
    public function initClass($type,$supportedClass, $params=[], $needInstance = true)
    {
        if(!array_key_exists($type, $supportedClass)) throw new Exception("$type 不在存在" );
        $className = $supportedClass[$type];
        return $needInstance ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;
    }
}