<?php
namespace EasySwoole\EasySwoole;


use App\Exception\ExceptionHandler;
use App\Pool\RedisPool;
use App\Process\Consumer;
use App\Websocket\WebSocketEvents;
use App\Websocket\WebSocketParser;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Pool\Manager;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Redis;
use EasySwoole\Socket\Dispatcher;
use EasySwoole\Utility\File;

class EasySwooleEvent implements Event
{



    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        if(!extension_loaded('yaconf')) {//查看php安装了yaconf扩展
            self::loadConf(EASYSWOOLE_ROOT.'/Conf');
        }

    }

    public static function mainServerCreate(EventRegister $register)
    {

        // TODO: Implement mainServerCreate() method.
        //获取连接数据库配置
        $mysqlConfig = getConf("mysql");

        DbManager::getInstance()->addConnection(new Connection(new \EasySwoole\ORM\Db\Config($mysqlConfig)));
        //注入redis
        $redisConfig = getConf('redis');
        Di::getInstance()->set("REDIS",new Redis(new RedisConfig($redisConfig)));//同步redis
        //连接池
        Manager::getInstance()->register(new RedisPool((new \EasySwoole\Pool\Config()),(new RedisConfig($redisConfig))),'redis');

        //消息队列
        $allNum = 3;
        for ($i = 0 ;$i < $allNum;$i++){
            ServerManager::getInstance()->getSwooleServer()->addProcess((new Consumer("consumer_{$i}"))->getProcess());
        }




        /**
         * **************** websocket控制器 **********************
         */
        $conf = new \EasySwoole\Socket\Config();

        $conf->setType(\EasySwoole\Socket\Config::WEB_SOCKET);//web_socket

        $conf->setParser(new WebSocketParser());
        // 创建 Dispatcher 对象 并注入 config 对象
        $dispatch = new Dispatcher($conf);

        //对象处理
        $register->set(EventRegister::onMessage, function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) use ($dispatch) {
            $dispatch->dispatch($server, $frame->data, $frame);
        });
        // 注册服务事件
        $register->add(EventRegister::onOpen, [WebSocketEvents::class, 'onOpen']);
        $register->add(EventRegister::onClose, [WebSocketEvents::class, 'onClose']);
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        return true;
    }
    
    
    

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }



    /**
     * Created by PhpStorm.
     * @Desc:加载配置文件
     * @User: shenhengxin
     * @Date: 2019/12/24
     * @Time: 15:26
     * @param $ConfPath
     */
    public static function loadConf($ConfPath)
    {
        $Conf = Config::getInstance();
        $files = File::scanDirectory($ConfPath);
        if(empty($files)) return ;
        foreach ($files['files'] as $file) {
            $data = require_once $file;
            $Conf->setConf(strtolower(basename($file,'.php')),(array)$data);
        }

    }
}