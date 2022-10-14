<?php

namespace App\core;

class Response
{
    public static function createResponse($data): array
    {
        if (array_key_exists('data', $data)) {
            return [
                'status' => true,
                'error' => null,
                'user_data' => $data['data']
            ];
        } elseif(array_key_exists('error', $data)) {
            return [
                'status' => false,
                'error' => $data['error'],
                'user_data' => null
            ];
        } elseif(array_key_exists('status', $data)) {
            return [
                'status' => boolval($data['status']),
                'error' => null,
                'user_data' => null
            ];
        }
    }
}