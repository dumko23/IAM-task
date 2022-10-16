<?php

namespace App\app\migrations;


use App\core\Application;
use App\core\database\MigrationsInterface;

class migration_0001_create_table_users implements MigrationsInterface
{


    public static function up(): bool
    {
        $result = Application::get('database')
            ->executeQuery(
                'USE `UsersManagement`;
                 CREATE TABLE `users` (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `name_first` varchar(35) NOT NULL,
                    `name_last` varchar(35) NOT NULL,
                    `status` varchar(10) NOT NULL,
                    `role` varchar(10) NOT NULL,
                    PRIMARY KEY (`id`)
                    ) '
            );
        if ($result === true) {
            return true;
        } else {
            print_r($result . PHP_EOL);
            return false;
        }
    }

    public static function down(): bool
    {
        $result = Application::get('database')
            ->executeQuery(
                'USE `UsersManagement`;
                 DROP TABLE `UsersManagement.users`'
            );
        if ($result === true) {
            return true;
        } else {
            print_r($result . PHP_EOL);
            return false;
        }
    }
}