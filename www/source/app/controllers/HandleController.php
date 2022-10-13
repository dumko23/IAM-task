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
        $userModel = new UserModel();
        $data = $userModel->showAll();
        return json_encode([
            "response" => $data,
        ]);
    }

    public function deleteOne(): bool|string
    {
        $request = $_POST["request"];
        $userModel = new UserModel();
        $data = $userModel->deleteOne($request["id"]);
        return json_encode([
            "response" => $data,
        ]);
    }
}