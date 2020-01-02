<?php


namespace App\Server\Upload;


class Video extends BaseService
{
    /**
     * @var string
     */
    public $fileType = "video";

    /**
     * 视频大小 30M
     * @var int
     */
    public $maxSize = 31457280;
    /**
     * 文件后缀
     * @var array
     */
    public $fileExtTypes = [
        'mp4','x-flv'
    ];
}