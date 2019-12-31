<?php
\EasySwoole\EasySwoole\Command\CommandContainer::getInstance()->set(new \App\Command\Test());

function asset($path) {

    $url = \EasySwoole\EasySwoole\Config::getInstance()->getConf('URL');
    return $url."/Resources/".$path;
}




