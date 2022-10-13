<?php

namespace App\core;

class Response
{
    public static function createResponse($data, $status, $error)
    {
        return [
            'status' => $status,
            'error' => $error,
            'user_data' => $data
        ];
    }
}