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

    public function delete(): string
    {
        $request = $_POST["request"];
        $userModel = new UserModel();
        $data = $userModel->deleteById($request["id"]);
        return json_encode([
            "response" => $data,
        ]);
    }

    public function addUser(): string
    {
        $request = $_POST["request"];
        $userModel = new UserModel();
        $data = $userModel->addUser($request["data"][0]);
        return json_encode([
            "response" => $data
        ]);
    }

    public function dropTable(): string
    {
        $userModel = new UserModel();
        $data = $userModel->deleteAll();
        return json_encode([
            "response" => $data
        ]);
    }

    public function updateStatus(): string
    {
        $request = $_POST['request'];

        $userModel = new UserModel();
        $data = $userModel->updateStatus($request);
        return json_encode([
            "response" => $data
        ]);
    }

    public function updateUser():string
    {
        $request = $_POST['request'];
        $userModel = new UserModel();
        $data = $userModel->updateUser($request);
        return json_encode([
            "response" => $data
        ]);
    }
}