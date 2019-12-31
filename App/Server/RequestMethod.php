<?php


namespace App\Server;


class RequestMethod
{
    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    public static function methods()
    {
        return [
            'post' => self::POST,
            'get' => self::GET,
            'put' => self::PUT,
            'delete' => self::DELETE
        ];
    }
}