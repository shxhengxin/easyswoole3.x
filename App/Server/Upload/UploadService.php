<?php


namespace App\Server\Upload;


use App\Lib\ReflectionClass;
use EasySwoole\Http\Exception\Exception;

class UploadService
{
    public static function upload($request)
    {
        $files = $request->getSwooleRequest()->files;
        $types = array_keys($files);

        $type = $types[0];
        if(empty($type)) throw new Exception("上传文件不合法");
        try{
            $classObj = new ReflectionClass();
            $classStats = $classObj->UploadClassStat();
            $uploadObj = $classObj->initClass($type,$classStats,[$request,$type]);
            $file = $uploadObj->upload();
        }catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
        if(empty($file)) throw new Exception("上传失败");
        return $file;
    }
}