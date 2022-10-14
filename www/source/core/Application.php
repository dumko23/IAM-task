<?php

namespace App\core;

use Exception;

class Application
{

    protected static array $registry = [];

    /**
     * Binds often used data to the registry
     *
     * @param  string  $key   storing key
     * @param  mixed  $value  stored data
     * @return void
     */
    public static function bind(string $key, mixed $value): void
    {
        static::$registry[$key] = $value;
    }

    /**
     * Get stored data from registry by given key
     *
     * @param  string  $key  key of stored data
     * @return mixed stored data
     * @throws Exception
     */
    public static function get(string $key): mixed
    {
        if (!array_key_exists($key, static::$registry)) {
            throw new Exception("No {$key} is bound in the container.");
        }
        return static::$registry[$key];
    }
}