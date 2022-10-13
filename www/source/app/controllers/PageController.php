<?php

namespace App\app\controllers;

use App\app\models\MembersModel;
use App\app\models\UserModel;
use App\core\Application;
use App\core\Controller;
use App\core\Model;

class PageController extends Controller
{

    public function main(): array
    {
        return [
            'path' => $this->returnPagePath('main'),
            'data' => '',
        ];
    }

    public function page404()
    {
        return [
            'path' => $this->returnPagePath('page404'),
            'data' => '',
        ];
    }

    public function showUsers()
    {
        return UserModel::showAll();
    }
}