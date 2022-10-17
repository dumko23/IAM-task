<?php

namespace App\core;

use Exception;

class Model
{
    /**
     * Add data to table (mentioned in config)
     *
     * @param  array  $data  data to add
     * @return array
     * @throws Exception
     */
    public function add(array $data): array
    {
        return Application::get('database')->insertToDB(
            Application::get('config')['database']['dbAndTable'],
            $data
        );
    }

    /**
     * Update data in DB
     *
     * @param  array  $data               new data
     * @param  string  $whereStatement    column name
     * @param  array  $searchedStatement  row that matches "where" statement
     * @return array
     * @throws Exception
     */
    public function update(array $data, string $whereStatement, array $searchedStatement): array
    {
        return Application::get('database')->updateDB(
            Application::get('config')['database']['dbAndTable'],
            $data,
            $whereStatement,
            $searchedStatement
        );
    }

    /**
     * Adding error definition to array that will be given as response
     *
     * @param  array  $errorList
     * @param  string  $name
     * @param  string  $message
     * @return array
     */
    public function addError(array $errorList, string $name, string $message): array
    {
        $errorList[$name] = $message;
        return $errorList;
    }

    /**
     * Forming fetch call to DB
     *
     * @param  string  $select        selectable data
     * @param  string  $dbAndTable
     * @param  string  $where         column name (optional, for specific data fetch)
     * @param  string  $searchedItem  row that matches "where" statement (optional, for specific data fetch)
     * @return array
     * @throws Exception
     */
    public function getData(string $select, string $dbAndTable, string $where = '', string $searchedItem = ''): array
    {
        return Application::get('database')->getFromDB($select, $dbAndTable, $where, $searchedItem);
    }

    /**
     * Validation method. checks the correctness of the provided data according to the given rules.
     * Keys of "rules" array should match keys of "record" array
     *
     * @param  array  $record  array of data to check
     * @param  array  $rules   array of rules
     * @return bool|array
     */
    public function validation(array $record, array $rules): bool|array
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
                $errors = $this->addError($errors, $fieldName, 'User role is not selected!');
            } elseif (str_contains($rule, 'rightRole') && !in_array($record[$fieldName], ['admin','user'])) {
                $errors = $this->addError($errors, $fieldName, 'Invalid role provided!');
            }
        }
        if (count($errors) === 0) {
            return true;
        } else {
            $result = [
                'error' => $errors
            ];
            unset($errors);
            return $result;
        }
    }

    /**
     * Delete data from table by given condition
     *
     * @param  string  $where             column name to search data
     * @param  array  $searchedStatement  row that matches "where" statement
     * @return array
     * @throws Exception
     */
    public function delete(string $where, array $searchedStatement): array
    {
        return Application::get('database')
            ->delete(
                Application::get('config')['database']['dbAndTable'],
                $where,
                $searchedStatement
            );
    }


    /**
     * TRUNCATE table
     *
     * @return array
     * @throws Exception
     */
    public function drop(): array
    {
        return Application::get('database')
            ->drop(
                Application::get('config')['database']['dbAndTable']
            );
    }


    public function getLastItem(){
        return Application::get('database')
            ->getLastItem(
                Application::get('config')['database']['dbAndTable'], "id"
            );
    }
}
