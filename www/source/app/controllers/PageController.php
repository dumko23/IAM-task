<?php

namespace App\app\controllers;

use App\app\models\MembersModel;
use App\app\models\UserModel;
use App\core\Application;
use App\core\Controller;
use App\core\Model;

class PageController extends Controller
{
    /**
     * Returns 'main' page
     *
     * @return array
     */
    public function main(): array
    {
        return [
            'path' => $this->returnPagePath('main'),
            'data' => '',
        ];
    }

    /**
     * Returns custom '404' page
     *
     * @return array
     */
    public function page404(): array
    {
        return [
            'path' => $this->returnPagePath('page404'),
            'data' => '',
        ];
    }
}