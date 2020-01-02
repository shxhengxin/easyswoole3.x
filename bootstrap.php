<?php
\EasySwoole\EasySwoole\Command\CommandContainer::getInstance()->set(new \App\Command\Test());

function asset($path) {

    $url = \EasySwoole\EasySwoole\Config::getInstance()->getConf('URL');
    return $url."/Resources/".$path;
}
if(!function_exists('getConf')) {
    function getConf($str){
        if(extension_loaded('yaconf')) {
            return  \Yaconf::get($str);
        }else{
            return  \EasySwoole\EasySwoole\Config::getInstance()->getConf($str);
        }

    }
}





