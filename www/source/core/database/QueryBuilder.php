<?php

namespace App\core\database;

use App\core\Application;
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

    /**
     * Fetches data from DB (can be specified)
     *
     * @param  string  $selectString  'select' statement
     * @param  string  $dbAndTable    DB and table name
     * @param  string  $where         column name
     * @param  string  $searchItem    row that matches with 'where' statement
     * @return array|array[] fetched data OR an array with an error description
     */
    public function getFromDB(string $selectString, string $dbAndTable, string $where = '', string $searchItem = ''): array
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

    /**
     * Insert new record with given data to DB
     *
     * @param  string  $dbAndTable  DB and table name
     * @param  array  $data         data to insert
     * @return array|array[] number of rows added OR an array with an error description
     */
    public function insertToDB(string $dbAndTable, array $data): array
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

    /**
     * Update one or more record in DB
     *
     * @param  string  $dbAndTable  DB and table name
     * @param  array  $data         data to update
     * @param  string  $where       column name
     * @param  array  $searchItem   row that matches with 'where' statement
     * @return array|array[]  [1] if the query was successful OR an array with an error description
     */
    public function updateDB(string $dbAndTable, array $data, string $where, array $searchItem): array
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

    /**
     * Delete one or more record in DB
     *
     * @param  string  $dbAndTable  DB and table name
     * @param  string  $where       column name
     * @param  array  $searchItem   row that matches with 'where' statement
     * @return array|array[] array with number of deleted records OR an array with an error description
     */
    public function delete(string $dbAndTable, string $where, array $searchItem): array
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

    /**
     * TRUNCATE table (delete all records at once)
     *
     * @param  string  $dbAndTable  DB and table name
     * @return array|array[] result OR an array with an error description
     */
    public function drop(string $dbAndTable): array
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

    /**
     * Forms an array with description of occurred error
     *
     * @param  Exception|PDOException  $e  occurred error
     * @return array[] array with error description
     */
    public function formError(Exception|PDOException $e): array
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


    /**
     * Query to execute in DB. Returns true if success, otherwise return array with error description.
     *
     * @param  string  $query  query string to execute.
     * @return bool|array
     */
    public function executeQuery(string $query): bool|array
    {
        try{
            $this->pdo->prepare($query)->execute();
            return true;
        } catch(Exception|PDOException $e) {
            return $this->formError($e);
        }
    }


    /**
     * Creates 'migration' table in DB. If error occurs - returns array with error description.
     *
     * @return array[]
     */
    public function createMigrationsTable(): array
    {
        try{
            $this->pdo->prepare(sprintf(
                    'CREATE TABLE IF NOT EXISTS %s.migrations (
                                        migration_name VARCHAR(255) NOT NULL UNIQUE,
                                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                        PRIMARY KEY(migration_name)
                            )',
                    Application::get('config')['database']['db'])
            )->execute();
            return [];
        } catch(Exception|PDOException $e) {
            return $this->formError($e);
        }
    }
}