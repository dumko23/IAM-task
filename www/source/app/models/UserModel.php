<?php

namespace App\app\models;

use App\core\Model;
use App\core\Response;
use Exception;

class UserModel extends Model
{
    /**
     * Return array of rules applicable to incoming data
     *
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name_first' => 'required|length:35|',
            'name_last' => 'required|length:30|',
            'role' => 'nullRole|'
        ];
    }

    /**
     * Returning response array with all users data in DB
     *
     * @return array
     */
    public function showAll(): array
    {
        return Response::createResponse($this->getData('users'));
    }

    /**
     * Deleting user from DB by id given in request
     *
     * @param  array  $data  request data containing user's id
     * @return array
     * @throws Exception
     */
    public function deleteById(array $data): array
    {
        $response = $this->delete("id", $data);
        return Response::createResponse($response);

    }

    /**
     * Adding user to DB with data given in request (if they pass validation process)
     *
     * @param  array  $data  array with new user data
     * @return array
     * @throws Exception
     */
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


    /**
     * Deleting all users from DB (i.e. TRUNCATING table)
     *
     * @return array
     */
    public function deleteAll(): array
    {
        $response = $this->drop();
        return Response::createResponse($response);
    }


    /**
     * Updating status of one or more users
     *
     * @param  array  $data  array with status value and user ids
     * @return array
     */
    public function updateStatus(array $data): array
    {
        $response = $this->update(['status' => $data['status']], 'id', $data['id']);
        return Response::createResponse($response);
    }

    /**
     * Updating user data in DB
     *
     * @param  array  $data  array with user updated data and id
     * @return array
     */
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