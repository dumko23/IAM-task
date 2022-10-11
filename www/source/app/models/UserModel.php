<?php

namespace App\app\models;

use App\core\Model;

class UserModel
{
    public static function showAll(): array
    {
        return Model::getData('users');
    }
}