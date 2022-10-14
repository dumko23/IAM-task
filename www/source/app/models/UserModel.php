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

    public function deleteById(array $data): array
    {
        $response = $this->delete($data);
        return Response::createResponse( true, null, $response);
    }

    public function addUser(array $request)
    {
        $response = $this->add($request);
        return Response::createResponse( true, null, $response);
    }

    public function deleteAll()
    {
        $response = $this->drop();
        return Response::createResponse( true, null, $response);
    }

    public function updateStatus(array $data)
    {
        $response = $this->update($data['status'], 'id', $data['id']);
        return Response::createResponse( true, null, $response);
    }

    public function updateUser(array $data)
    {
        $response = $this->update($data['data'][0],'id', $data['id']);
        return Response::createResponse( true, null, $response);
    }
}