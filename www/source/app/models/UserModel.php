<?php

namespace App\app\models;

use App\core\Model;
use App\core\Response;

class UserModel extends Model
{
    public function showAll(): array
    {
        return Response::createResponse( true, null, $this->getData('users'));
    }

    public function deleteOne(array $data): array
    {
        $response = $this->delete($data);
        return Response::createResponse( true, null, $response);
    }
}