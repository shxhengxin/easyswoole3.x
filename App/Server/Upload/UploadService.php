<?php


namespace App\Server\Upload;

use EasySwoole\Http\Message\Status;
use Response\ApiResponse;

class UploadService
{
    public static function upload($img_file)
    {
        if(!$img_file) return   ApiResponse::returnResults(Status::CODE_GONE,"请选择上传的文件");
        if($img_file->getSize() > 1024*1024*2)  return   ApiResponse::returnResults(Status::CODE_GONE,"图片不能大于2M");
        $mediaType = explode("/",$img_file->getClientMediaType());
        $mediaType = $mediaType[1] ?? "";
        if(!in_array($mediaType,['png', 'jpg', 'gif', 'jpeg', 'pem', 'ico']))  return   ApiResponse::returnResults(Status::CODE_GONE,"文件类型不正确！");

        $path = "/Storage/upload/".date("Y-m-d",time()) ."/";
        $dir = EASYSWOOLE_ROOT . $path;
        $fileName = uniqid().$img_file->getClientFileName();
        if(!is_dir($dir)) {
            mkdir($dir,0777,true);
        }
        $flag = $img_file->moveTo($dir.$fileName);

        $data = ['name'=>$fileName,'src'=>$path.$fileName];

        if($flag) return ApiResponse::returnResults(Status::CODE_OK,"上传成功",$data);
        return ApiResponse::returnResults(Status::CODE_GONE,"上传失败");

    }
}