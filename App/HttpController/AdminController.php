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
     * @desc 无需登录，访问的路由
     * @var array
     */
    private $basicAction = [
        '/admin/login',
        '/admin/register',
        '/admin/upload',
    ];

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
        if(!parent::onRequest($action)){
            return  false;
        }
        $path = $this->request()->getSwooleRequest()->server['request_uri'];
        //无需登录直接访问
        if(!in_array($path,$this->basicAction)) {

        }

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