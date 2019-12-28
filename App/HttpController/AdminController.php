<?php


namespace App\HttpController;

use duncan3dc\Laravel\Blade;
use duncan3dc\Laravel\BladeInstance;

/**
 * Class AdminController 后台基础控制器
 * @package App\HttpController
 */
class AdminController extends CommonController
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

    public function render(string $view, array $params = [])
    {
        $path = EASYSWOOLE_ROOT.'/Resources/views';
        $cache_path = EASYSWOOLE_ROOT.'/Storage/framework/cache/views';
        $blade = new BladeInstance($path,$cache_path);
        $content = $blade->render($view,$params);
        return $this->response()->write($content);

    }
}