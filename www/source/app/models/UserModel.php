<?php

namespace App\app\models;

use App\core\Model;
use App\core\Response;

class UserModel extends Model
{
    public function rules(): array
    {
        return [
            'name_first' => 'required|length:35|',
            'name_last' => 'required|length:30|',
            'role' => 'nullRole|'
        ];
    }

    public function showAll(): array
    {
        return Response::createResponse($this->getData('users'));
    }

    public function deleteById(array $data): array
    {
        $response = $this->delete($data);
        return Response::createResponse($response);

    }

    public function addUser(array $data): array
    {
        $validation = $this->validation($data, $this->rules());
        if ($validation === true) {
            $response = $this->add($data);
            return Response::createResponse($response);
        } else {
            return Response::createResponse($validation);
        }
    }

    public function deleteAll(): array
    {
        $response = $this->drop();
        return Response::createResponse($response);
    }

    public function updateStatus(array $data): array
    {
        $response = $this->update(['status' => $data['status']], 'id', $data['id']);
        return Response::createResponse($response);
    }

    public function updateUser(array $data): array
    {
        $validation = $this->validation($data['data'][0], $this->rules());
        if ($validation === true) {
            $response = $this->update($data['data'][0], 'id', $data['id']);
            return Response::createResponse($response);
        } else {
            return Response::createResponse($validation);
        }
    }
}