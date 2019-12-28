<?php


namespace App\Websocket\Controller;

use EasySwoole\Socket\AbstractInterface\Controller;

class Index extends Controller
{
    public function hello()
    {

        $this->response()->setMessage('call hello with arg :' .$this->caller()->getArgs()['content']);
    }
}