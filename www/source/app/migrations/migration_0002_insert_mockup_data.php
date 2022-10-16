<?php

namespace App\app\migrations;

use App\core\Application;
use App\core\database\MigrationsInterface;

class migration_0002_insert_mockup_data implements MigrationsInterface
{

    public static function up(): bool
    {
        $result = Application::get('database')
            ->executeQuery(
                "USE `UsersManagement`;
                 INSERT INTO `users` (`id`, `name_first`, `name_last`, `status`, `role`) VALUES
                    (1,	'adam',	'hecker',	'true',	'admin'),
                    (2,	'adam',	'heckerini',	'false',	'user'),
                    (3,	'jon',	'dinner',	'false',	'user'),
                    (4,	'qwe',	'qwe',	'true',	'admin'),
                    (5,	'asd',	'asdsd',	'false',	'admin'),
                    (6,	'qwew',	'wewe',	'true',	'admin')"
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
                "USE `UsersManagement`;
                 DELETE FROM `UsersManagement.users` WHERE id IN (`1`, `2`, `3`, `4`, `5`, `6`)"
            );
        if ($result === true) {
            return true;
        } else {
            print_r($result . PHP_EOL);
            return false;
        }
    }
}