<?php


namespace App\Server\Upload;


class Image extends BaseService
{
    /**
     * @var string
     */
    public $fileType = "image";

    /**
     * 图片大小  5M
     * @var int
     */
    public $maxSize = 5242880*2;
    /**
     * 文件后缀
     * @var array
     */
    public $fileExtTypes = [
        'png', 'jpg', 'gif', 'jpeg', 'pem', 'ico'
    ];
}