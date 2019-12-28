<?php


namespace App\HttpController;

use EasySwoole\FastCache\Cache;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\Request;
use EasySwoole\Http\AbstractInterface\AnnotationController;

/**
 * Class BaseController api基础控制器
 * @package App\HttpController
 */
class BaseController extends CommonController
{

    /**
     * Created by PhpStorm.
     * @Desc:做权限认证
     * @User: shenhengxin
     * @Date: 2019/12/19
     * @Time: 17:42
     * @param string|null $action
     * @return bool|null
     */
    protected function onRequest(?string $action): ?bool
    {
        return true;
    }


    public function  onException(\Throwable $throwable): void
    {
        $this->writeJson(400,[],$throwable->getMessage());
    }



}