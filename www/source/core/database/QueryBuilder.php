<?php

namespace App\core\database;

use Exception;
use PDO;
use PDOException;

class QueryBuilder
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getFromDB(string $selectString, $dbAndTable, $where = '', $searchItem = ''): array
    {
        try {
            if ($searchItem !== '') {
                $searchItem = "'$searchItem'";
            }
            $sql = sprintf("select %s from %s %s %s", $selectString, $dbAndTable, $where, $searchItem);
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return [
                'data' => $statement->fetchAll()
            ];
        } catch (Exception|PDOException $e) {
            return $this->formError($e);
        }
    }

    public function insertToDB($dbAndTable, $data): array
    {
        try {
            $sql = sprintf('insert into %s (%s) values(%s)',
                $dbAndTable,
                implode(', ', array_keys($data)),
                str_repeat('?,', count($data) - 1) . '?');
            $statement = $this->pdo->prepare($sql);
            return [
                'status' => $statement->execute(array_values($data))
            ];
        } catch (Exception|PDOException $e) {
            return $this->formError($e);
        }
    }

    public function updateDB($dbAndTable, $data, $where, $searchItem): array
    {
        try {
            $sql = sprintf("update %s set %s  where %s in (%s)",
                $dbAndTable,
                implode(' = ?, ', array_keys($data)) . ' = ?',
                $where,
                implode(',', $searchItem)
            );
            $statement = $this->pdo->prepare($sql);
            return [
                'status' => $statement->execute(array_values($data))
            ];
        } catch (Exception|PDOException $e) {
            return $this->formError($e);
        }
    }

    public function delete($dbAndTable, $where, $searchItem): array
    {
        try {
            $sql = sprintf("DELETE from %s WHERE %s IN (%s)",
                $dbAndTable,
                $where,
                implode(',', $searchItem)
            );
            $statement = $this->pdo->prepare($sql);
            return [
                'status' => $statement->execute()
            ];
        } catch (Exception|PDOException $e) {
            return $this->formError($e);
        }
    }

    public function drop($dbAndTable): array
    {
        try {
            $sql = sprintf("TRUNCATE TABLE %s",
                $dbAndTable);
            $statement = $this->pdo->prepare($sql);
            return [
                'status' => $statement->execute()
            ];
        } catch (Exception|PDOException $e) {
            return $this->formError($e);
        }
    }

    public function formError($e): array
    {
        return [
            'error' => [
                'code' => $e->getCode(),
                'gotIn' => $e->getFile(),
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]
        ];
    }
}