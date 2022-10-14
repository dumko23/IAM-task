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
        $response = $userModel->showAll();
        return json_encode($response);
    }

    public function delete(): string
    {
        $request = $_POST["request"];
        $userModel = new UserModel();
        $response = $userModel->deleteById($request["id"]);
        return json_encode($response);
    }

    public function addUser(): string
    {
        $request = $_POST["request"];
        $userModel = new UserModel();
        $response = $userModel->addUser($request["data"][0]);
        return json_encode($response);
    }

    public function dropTable(): string
    {
        $userModel = new UserModel();
        $response = $userModel->deleteAll();
        return json_encode($response);
    }

    public function updateStatus(): string
    {
        $request = $_POST['request'];
        $userModel = new UserModel();
        $response = $userModel->updateStatus($request);
        return json_encode($response);
    }

    public function updateUser():string
    {
        $request = $_POST['request'];
        $userModel = new UserModel();
        $response = $userModel->updateUser($request);
        return json_encode($response);
    }
}