<?php

namespace App\core;

class Request
{
    /**
     * Returns requested uri
     *
     * @param  string  $request  request
     * @return string uri
     */
    public static function getUri(string $request): string
    {
        return trim($request, '/');
    }

    /**
     * Returns request method
     *
     * @return string method
     */
    public static function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}