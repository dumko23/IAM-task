<?php

namespace App\app\models;

use App\core\Application;
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
            'role' => 'nullRole|rightRole|'
        ];
    }

    /**
     * Returning response array with all users data in DB
     *
     * @return array
     * @throws Exception
     */
    public function showAll(): array
    {
        return Response::createResponse(
            $this->getData(
                'name_first, name_last, role, status, id',
                Application::get('config')['database']['dbAndTable']
            )
        );
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
        $result = Response::createResponse($response);
        if ($result['error'] !== null) {
            return Response::createResponse($result);
        } else {
            return Response::createResponse(['changed_id' => $data]);
        }
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
        $data['name_first'] = htmlspecialchars(strip_tags(trim($data['name_first'])));
        $data['name_last'] = htmlspecialchars(strip_tags(trim($data['name_last'])));
        $validation = $this->validation($data, $this->rules());
        if ($validation === true) {
            $result = $this->add($data);
            if (array_key_exists('error', $result) === true) {
                return Response::createResponse($result);
            }
            $response = $this->getLastItem();
            return Response::createResponse($response);
        } else {
            return Response::createResponse($validation);
        }
    }


    /**
     * Deleting all users from DB (i.e. TRUNCATING table)
     *
     * @return array
     * @throws Exception
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
     * @throws Exception
     */
    public function updateStatus(array $data): array
    {
        $result = $this->update(['status' => $data['status']], 'id', $data['id']);

        if (key_exists("error", $result)) {
            return Response::createResponse($result);
        } else {
            return Response::createResponse(['changed_id' => $data['id']]);
        }
    }

    /**
     * Updating user data in DB
     *
     * @param  array  $data  array with user updated data and id
     * @return array
     * @throws Exception
     */
    public function updateUser(array $data): array
    {
        $data['data'][0]['name_first'] = htmlspecialchars(strip_tags(trim($data['data'][0]['name_first'])));
        $data['data'][0]['name_last'] = htmlspecialchars(strip_tags(trim($data['data'][0]['name_last'])));
        $validation = $this->validation($data['data'][0], $this->rules());
        if ($validation === true) {
            $result = $this->update($data['data'][0], 'id', $data['id']);
            return Response::createResponse($result);
        } else {
            return Response::createResponse($validation);
        }
    }
}