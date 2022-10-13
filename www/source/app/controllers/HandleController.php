<?php

namespace App\app\controllers;

use App\app\models\MembersModel;
use App\app\models\UserModel;
use App\core\Application;
use App\core\Controller;

class HandleController extends Controller
{
    public function getUserList(): string
    {
        $data = UserModel::showAll();
        return json_encode([
            "userData" => $data,
        ]);
    }
}