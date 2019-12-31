<?php


namespace App\HttpController;


use App\route\Route;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use FastRoute\RouteCollector;

/**
 * Class Router 基础路由
 * @package App\HttpController
 */
class Router extends AbstractRouter
{

    function initialize(RouteCollector $routeCollector)
    {
        $this->setGlobalMode(true);
        $this->setRouterNotFoundCallBack(function (Request $request, Response $response) {
            $this->ResponseJson($response,Status::CODE_NOT_FOUND,"未找到路由匹配");
        });
        $this->setMethodNotAllowCallBack(function (Request $request, Response $response) {
            $this->ResponseJson($response,Status::CODE_NOT_FOUND,"未找到处理方法");
        });

        Route::getInstance($routeCollector)->route();

    }

    public function ResponseJson($response,$status, $msg = null ,$result = null)
    {
        if (!$response->isEndResponse()) {
            $data = [
                "code" => $status,
                "result" => $result,
                "msg" => $msg
            ];
            $str = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $response->write($str);
            $response->withHeader('Content-type', 'application/json;charset=utf-8');
            $response->withStatus($status);
            return true;
        } else {
            return false;
        }
    }



}