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
            'role' => 'null|'
        ];
    }

    public function showAll(): array
    {
        return Response::createResponse(true, null, $this->getData('users'));
    }

    public function deleteById(array $data): array
    {
        $response = $this->delete($data);
        return Response::createResponse(true, null, $response);

    }

    public function addUser(array $data): array
    {
        $validation = $this->validation($data);
        if ($validation === true) {
            $response = $this->add($data);
            return Response::createResponse(true, null, $response);
        } else {
            return Response::createResponse(false, $validation, null);
        }
    }

    public function deleteAll(): array
    {
        $response = $this->drop();
        return Response::createResponse(true, null, $response);
    }

    public function updateStatus(array $data): array
    {
        if ($data['action'] === 'setActive') {
            $data['status'] = [
                'status' => 'true'
            ];
        } elseif ($data['action'] === 'setInactive') {
            $data['status'] = [
                'status' => 'false'
            ];
        }
        $response = $this->update($data['status'], 'id', $data['id']);
        return Response::createResponse(true, null, $response);
    }

    public function updateUser(array $data): array
    {
        $validation = $this->validation($data['data'][0]);
        if ($validation === true) {
            $response = $this->update($data['data'][0], 'id', $data['id']);
            return Response::createResponse(true, null, $response);
        } else {
            return Response::createResponse(false, $validation, null);
        }
    }
}