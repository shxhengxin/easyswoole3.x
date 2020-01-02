<?php


namespace App\Server\Upload;

use App\Lib\Utils;
use EasySwoole\Http\Exception\Exception;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class BaseService
{
    private $request;
    /**
     * @var  对象
     */
    private $obj;

    /**
     * 上传文件的file -- key
     * @var string
     */
    private $type = '';

    /**
     * @var 大小
     */
    private $size;

    /**
     * @var  返回前端文件路径
     */
    public $file;

    public function __construct($request,$type=null)
    {
        $this->request = $request;
        if (empty($type)){
            $files = $this->request->getSwooleRequest()->files;
            $types = array_keys($files);
            $this->type = $types[0];
        }else{
            $this->type = $type;
        }
    }

    public function upload()
    {
        if($this->type !== $this->fileType) {
            throw new Exception("参数只能是 【 $this->fileType 】");
        }
        $this->obj = $this->request->getUploadedFile($this->type);

        $this->size = $this->obj->getSize();
        $this->checkSize(); //检查上传文件大小
        $fileName = $this->obj->getClientFileName();//文件名
        $this->checkMediaType();//文件后缀是否合法
        $this->getFile($fileName);//重组文件
        $flag = $this->obj->moveTo($this->file);
        if(empty($flag)) throw new Exception("上传失败");
        return $this->uploadFile($this->file);




    }


    private function uploadFile($filePath)
    {
        $qiniuConfig = getConf('qiniu');
        $auth = new Auth($qiniuConfig['AccessKey'],$qiniuConfig['SecretKey']);
        $token = $this->getToken($auth,$qiniuConfig['bucket']);
        $uploadMgr = new UploadManager(); //
        list($ret,$err) = $uploadMgr->putFile($token,null,$filePath);
        if(!empty($err))  throw new Exception("上传失败");
        @unlink($filePath);
        $url =  $qiniuConfig['domain'].'/'.$ret['key'];
        return $auth->privateDownloadUrl($url);

    }

    private function getToken($auth,$bucket) {
        //创建一个过期时间的临时上传令牌
        $token = $auth->uploadToken($bucket);
        return $token;
    }

    /**
     * 获取文件
     * @param $fileName
     * @return string
     */
    public function getFile($fileName)
    {
        $pathinfo = pathinfo($fileName);
        $extension = $pathinfo['extension'];
        $path = "/Storage";
        $dir =  EASYSWOOLE_ROOT .$path;
        if(!is_dir($dir)) mkdir($dir,0777,true);
        $basename = "/" . Utils::getFileKey($fileName) . "." . $extension;
        $this->file = $dir . $basename;
        return $path.$basename;
    }

    /**
     * 检查上传文件大小
     * @return bool
     */
    public function checkSize()
    {
        if(empty($this->size)) throw new Exception("上传文件失败");
        if($this->size > $this->maxSize) throw new Exception("上传文件不能大于 $this->maxSize M");
    }

    /**
     * 检测文件是否合法
     * @return bool
     * @throws \Exception
     */
    public function checkMediaType()
    {
        $clientMediaType = explode("/",$this->obj->getClientMediaType());
        $clientMediaType = $clientMediaType[1] ?? "";
        if(empty($clientMediaType)) throw new Exception("上传{ $this->type }文件不合法" );
        if(!in_array($clientMediaType,$this->fileExtTypes)) throw new Exception("上传{ $this->type }文件不合法" );
        return true;
    }
}