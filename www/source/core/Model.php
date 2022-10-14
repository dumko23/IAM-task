<?php

namespace App\core;

class Model
{
    public function add(array $data)
    {
        return Application::get('database')->insertToDB(
            Application::get('config')['database']['dbAndTable'],
            $data
        );
    }

    public function update($data, $whereStatement, $searchedStatement)
    {
        return Application::get('database')->updateDB(
            Application::get('config')['database']['dbAndTable'],
            $data,
            $whereStatement,
            $searchedStatement
        );
    }

    public function addError($errorList, $name, $message)
    {
        $errorList[$name] = $message;
        return $errorList;
    }

    public function getData($data, $where = '', $searchedItem = '')
    {
        if ($data === 'users') {
            $select = 'name_first, name_last, role, status, id';
            $dbAndTable = Application::get('config')['database']['dbAndTable'];
        }
        return Application::get('database')->getFromDB($select, $dbAndTable, $where, $searchedItem);
    }

    public function validation($record, $rules): bool|string
    {
        $errors = [];

        foreach ($rules as $fieldName => $rule) {
            if (str_contains($rule, 'required') && $record[$fieldName] === '') {
                $errors = $this->addError($errors, $fieldName, 'Input is empty!');
            } elseif (str_contains($rule, 'maxlength')) {
                preg_match('/(?<=maxlength:)(\d+)(?=\|)/U', $rule, $matches, PREG_OFFSET_CAPTURE);
                if (strlen($matches[0][0]) > $rule['max']) {
                    $errors = $this->addError($errors, $fieldName, "Input length should be maximum {$matches[0][0]} symbols!");
                }
            } elseif (str_contains($rule, 'nullRole') && $record[$fieldName] === '') {
                $errors = $this->addError($errors, $fieldName, 'Select role!');
            }
        }
        if (count($errors) === 0) {
            return true;
        } else {
            $result = json_encode($errors);
            unset($errors);
            return $result;
        }
    }

    public function delete(array $data)
    {
        return Application::get('database')
            ->delete(
                Application::get('config')['database']['dbAndTable'],
                "id",
                $data
            );
    }

    public function drop()
    {
        return Application::get('database')
            ->drop(
                Application::get('config')['database']['dbAndTable']
            );
    }
}
