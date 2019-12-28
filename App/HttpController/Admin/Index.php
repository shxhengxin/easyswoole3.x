<?php


namespace App\HttpController\Admin;


use App\HttpController\AdminController;
use App\Server\IndexService;

class Index extends AdminController
{
    public function getVideo()
    {
        $arr = (new IndexService())->getVideo();


        return $this->render('index');

        //return $this->writeJson($arr['status'],$arr['list'],$arr['msg']);
    }
}