<?php

namespace App\core;

class Response
{
    /**
     * Forms response array according to received data
     *
     * @param  array  $data  data from outer methods
     * @return array response to be sent back
     */
    public static function createResponse(array $data): array
    {
        if (array_key_exists('name_first', $data)) {
            return [
                'status' => true,
                'error' => null,
                'user_data' => $data
            ];
        } elseif (array_key_exists('changed_id', $data)) {
            return [
                'status' => true,
                'error' => null,
                'id' => $data['changed_id']
            ];
        } elseif (array_key_exists('data', $data)) {
            return [
                'status' => true,
                'error' => null,
                'user_data' => $data['data']
            ];
        } elseif (array_key_exists('error', $data)) {
            return [
                'status' => false,
                'error' => $data['error'],
            ];
        } elseif (array_key_exists('status', $data)) {
            return [
                'status' => boolval($data['status']),
                'error' => null,
            ];
        }
    }
}