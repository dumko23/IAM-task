<?php

namespace App\core\database;

use PDO;

class QueryBuilder
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getFromDB(string $selectString, $dbAndTable, $where = '', $searchItem = ''): bool|array
    {
        if ($searchItem !== '') {
            $searchItem = "'$searchItem'";
        }
        $sql = sprintf("select %s from %s %s %s", $selectString, $dbAndTable, $where, $searchItem);
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function insertToDB($dbAndTable, $data): bool
    {
        $sql = sprintf('insert into %s (%s) values(%s)',
            $dbAndTable,
            implode(', ', array_keys($data)),
            str_repeat('?,', count($data) - 1) . '?');
        $statement = $this->pdo->prepare($sql);
        return $statement->execute(array_values($data));
    }

    public function updateDB($dbAndTable, $data, $where, $searchItem): void
    {
        $sql = sprintf("update %s set %s  where %s in (%s)",
            $dbAndTable,
            implode(' = ?, ', array_keys($data)) . ' = ?',
            $where,
            implode(',', $searchItem)
        );
        $statement = $this->pdo->prepare($sql);
        $statement->execute(array_values($data));
    }

    public function delete($dbAndTable, $where, $searchItem): bool
    {
        $sql = sprintf("DELETE from %s WHERE %s IN (%s)",
            $dbAndTable,
            $where,
            implode(',', $searchItem)
        );
        $statement = $this->pdo->prepare($sql);
        return $statement->execute();
    }

    public function drop($dbAndTable): bool
    {
        $sql = sprintf("TRUNCATE TABLE %s",
            $dbAndTable);
        $statement = $this->pdo->prepare($sql);
        return $statement->execute();
    }
}