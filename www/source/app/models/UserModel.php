<?php

namespace App\app\models;

use App\core\Model;
use App\core\Response;

class UserModel
{
    public static function showAll(): array
    {
        return Response::createResponse(Model::getData('users'), true, null);
    }
}