<?php


namespace App\route;


use EasySwoole\Component\Singleton;

class Route
{
    use Singleton;
    private $routeCollector;
    public function __construct($routeCollector)
    {
        $this->routeCollector = $routeCollector;
    }

    public  function route()
    {
        $this->routeCollector->addGroup("/api",function (){
            $this->routeCollector->get('/rpc','/api/index/getVideo');
            $this->routeCollector->get('/redis','/api/index/getRedis');
            $this->routeCollector->get('/pub/{f}','/api/index/pub');
        });




        $this->routeCollector->addGroup("/admin",function (){
            $this->routeCollector->get("/login","/admin/login/login");
        });


    }
}