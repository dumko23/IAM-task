<?php

namespace App\core\database;

use PDO;
use PDOException;

class PDOClass
{
    private static PDO $db;

    /**
     * Creates new PDO instance (if it doesn't exist already)
     *
     * @param  array  $config credentials for DB connection
     * @return PDO
     */
    public static function connection(array $config): PDO
    {
        if (!isset(self::$db)) {
            self::$db = new PDO($config['name'] . 'dbname:' . $config['db'],
                $config['user'], $config['password'], $config['options']);
        }
        return self::$db;
    }

    /**
     * Returns existing PDO instance
     *
     * @return PDO
     */
    public static function getDb(): PDO
    {
        return self::$db;
    }
}