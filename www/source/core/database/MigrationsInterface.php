<?php

namespace App\core\database;

use App\core\Application;

interface MigrationsInterface
{
    /**
     * Action to be called if migration process starts.
     *
     * @return bool
     */
    public static function up(): bool;
    // Possible realization
//    {
//        $result = Application::get('database')
//            ->executeQuery(
//                'USE `UsersManagement`;
//                 CREATE TABLE `users` (
//                    `id` int NOT NULL AUTO_INCREMENT,
//                    `name_first` varchar(35) NOT NULL,
//                    `name_last` varchar(35) NOT NULL,
//                    `status` varchar(10) NOT NULL,
//                    `role` varchar(10) NOT NULL,
//                    PRIMARY KEY (`id`)
//                    ) '
//            );
//        if ($result === true) {
//            return true;
//        } else {
//            print_r($result . PHP_EOL);
//            return false;
//        }
//    }


    /**
     * Action to be called if rollback process starts.
     *
     * @return bool
     */
    public static function down(): bool;
    // Possible realization
//    {
//        $result = Application::get('database')
//            ->executeQuery(
//                'USE `UsersManagement`;
//                 DROP TABLE `UsersManagement.users`'
//            );
//        if ($result === true) {
//            return true;
//        } else {
//            print_r($result . PHP_EOL);
//            return false;
//        }
//    }
}