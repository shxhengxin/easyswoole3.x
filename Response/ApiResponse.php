<?php


namespace Response;


class ApiResponse
{
    public static function returnResults($status=200,$msg='',$data = [])
    {
        return [
            "status" => $status,
            "msg" => $msg,
            "list" => $data,
        ];

    }
}