<?php
\EasySwoole\EasySwoole\Command\CommandContainer::getInstance()->set(new \App\Command\Test());

function asset($path) {
    return EASYSWOOLE_ROOT ."/Resources/".$path;
}