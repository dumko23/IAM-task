<?php

namespace App\app\controllers;

use App\app\models\MembersModel;
use App\app\models\UserModel;
use App\core\Application;
use App\core\Controller;
use Exception;

class HandleController extends Controller
{

    /**
     * Invoking UserModel to fetch data from DB
     *
     * @return string JSON response
     */
    public function getUserList(): string
    {
        $userModel = new UserModel();
        $response = $userModel->showAll();
        return json_encode($response);
    }

    /**
     * Invoking UserModel to delete data from DB
     *
     * @return string JSON response
     * @throws Exception
     */
    public function delete(): string
    {
        $request = $_POST["request"];
        $userModel = new UserModel();
        $response = $userModel->deleteById($request["id"]);
        return json_encode($response);
    }

    /**
     * Invoking UserModel to add user to DB
     *
     * @return string JSON response
     * @throws Exception
     */
    public function addUser(): string
    {
        $request = $_POST["request"];
        $userModel = new UserModel();
        $response = $userModel->addUser($request["data"][0]);
        return json_encode($response);
    }

    /**
     * Invoking UserModel to TRUNCATE table in DB
     *
     * @return string JSON response
     * @throws Exception
     */
    public function dropTable(): string
    {
        $userModel = new UserModel();
        $response = $userModel->deleteAll();
        return json_encode($response);
    }

    /**
     * Invoking UserModel to update user status in DB
     *
     * @return string JSON response
     * @throws Exception
     */
    public function updateStatus(): string
    {
        $request = $_POST['request'];
        $userModel = new UserModel();
        $response = $userModel->updateStatus($request);
        return json_encode($response);
    }

    /**
     * Invoking UserModel to update user data in DB
     *
     * @return string JSON response
     * @throws Exception
     */
    public function updateUser(): string
    {
        $request = $_POST['request'];
        $userModel = new UserModel();
        $response = $userModel->updateUser($request);
        return json_encode($response);
    }
}