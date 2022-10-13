<?php

namespace App\core;

class Response
{
    public static function createResponse($status, $error, $data): array
    {
        return [
            'status' => $status,
            'error' => $error,
            'user_data' => $data
        ];
    }
}