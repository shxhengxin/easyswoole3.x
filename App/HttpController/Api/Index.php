<?php


namespace App\HttpController\Api;


use App\HttpController\BaseController;
use App\Server\IndexService;
use EasySwoole\Component\Di;
use EasySwoole\Pool\Manager;

class Index extends BaseController
{
    public function index()
    {
        $data = [
            'id' => 1,
            'name' => 'iwanli2',
            'params' => $this->request()->getRequestParam()
        ];
        return $this->writeJson(200,$data,'成功');
    }

    public function getVideo()
    {
         $arr = (new IndexService())->getVideo();
         return $this->writeJson($arr['status'],$arr['list'],$arr['msg']);
    }


    public function getRedis()
    {
        /*$redis = new \Redis();
        $redis->connect("127.0.0.1",6379,5);
        $redis->set("singwa456",90);*/
        //$redis = Redis::getInstance()->get("singwa456");
        $redis = Di::getInstance()->get("REDIS")->get("singwa456");

        $obj = Manager::getInstance()->get('redis')->getObj();
        $list = $obj->get("singwa456");

        Manager::getInstance()->get('redis')->recycleObj($obj);
        $arr = ['redis'=>$redis,'list' => $list];
        return $this->writeJson(200,$arr,'成功');
    }

    public function pub()
    {

        $params = $this->request()->getRequestParam();
        $obj = Manager::getInstance()->get('redis')->getObj();
        $obj->rPush("task_list",$params['f']);
    }
}