<?php


namespace App\Websocket;


class WebSocketEvents
{
    public static function onOpen(\swoole_websocket_server $server, \swoole_http_request $request)
    {
        $server->push($request->fd, "客户端:".$request->fd."连接上了\n");
    }

    public static function onClose(\swoole_server $server, int $fd, int $reactorId)
    {
        $server->push($fd->fd, "客户端:".$fd->fd."断开连接了\n");
    }
}