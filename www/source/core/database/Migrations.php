<?php

namespace App\core\database;

use App\core\Application;
use App\core\Model;
use Exception;

class Migrations
{
    /**
     * Runs migration process. Calls up() methods in migrations classes from 'app/migrations/' .
     * Logs every action in STDOUT. Applied migrations stored in 'migrations' table in DB in order of application.
     *
     * @return void
     * @throws Exception
     */
    public function applyMigrations(): void
    {
        $this->message("Starting migration process.");
        $this->initiateMigrationsTable();
        $dir = __DIR__ . '/../..';
        $migrations = scandir($dir . '/app/migrations');
        $appliedMigrations = array_column($this->getAppliedMigrations()['data'], 'migration_name');
        $newMigrations = [];
        foreach ($migrations as $key => $migration) {
            if ($migration === '.' || $migration === '..') {
                unset($migrations[$key]);
                continue;
            }
            $migration = str_replace('.php', '', $migration);
            $migrationToApply = "App\app\migrations\\$migration";
            if (!method_exists($migrationToApply, 'up') || !method_exists($migrationToApply, 'down')) {
                $this->message("$migrationToApply doesn't implement Migration Interface. Stopping migration process..");
                break;
            }
            if (!in_array($migration, $appliedMigrations)) {
                if ($migrationToApply::up()) {
                    if ($this->storeMigration($migration)) {
                        $this->message("Successfully applied migration '$migration'.");
                    } else {
                        $this->message("Error while storing migration '$migration'. Stopping migration process..");
                        break;
                    }
                } else {
                    $this->message("Error while applying migration '$migration'. Please, check code and try again.");
                    break;
                }
            } else {
                continue;
            }
            $newMigrations[] = $migration;
        }
        $count = count($newMigrations);
        $this->message("Applied $count migrations.");
    }


    /**
     * Creates migration table in DB.
     *
     * @return void
     * @throws Exception
     */
    public function initiateMigrationsTable(): void
    {
        Application::get('database')
            ->createMigrationsTable(
                Application::get('config')['database']['db']
            );
    }


    /**
     * Fetch already applied migrations from 'migrations' table in DB.
     *
     * @return array array of applied migrations
     * @throws Exception
     */
    public function getAppliedMigrations(): array
    {
        return (new Model)->getData(
            'migrations',
            'migration_name',
            Application::get('config')['database']['db'] . '.migrations'
        );
    }


    /**
     * Rollback every applied migration in reverse order. Migrations will be deleted from 'migrations' table in DB.
     * Logs every action in STDOUT.
     *
     * @return void
     * @throws Exception
     */
    public function rollback(): void
    {
        $this->message("Rolling back.");
        $appliedMigrations = array_column($this->getAppliedMigrations()['data'], 'migration_name');
        rsort($appliedMigrations);
        foreach ($appliedMigrations as $migration) {
            $migrationToApply = "App\app\migrations\\$migration";
            if (!method_exists($migrationToApply, 'up') || !method_exists($migrationToApply, 'down')) {
                $this->message("$migrationToApply doesn't implement Migration Interface. Stopping migration process..");
                break;
            }
            if ($migrationToApply::down()) {
                if ($this->deleteMigration($migration)) {
                    $this->message("Successfully rolled back migration '$migration'.");
                } else {
                    $this->message("Error while rolling back migration '$migration'. Stopping process..");
                    die();
                }
            } else {
                $this->message("Error while applying migration '$migration'. Please, check code and try again.");
                die();
            }
        }
        $count = count($appliedMigrations);
        $this->message("Rolled back $count migrations.");
    }


    /**
     * Logs action in STDOUT.
     *
     * @param  string  $message
     * @return void
     */
    public function message(string $message): void
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }


    /**
     * Stores migration file name in 'migrations' table in DB. Checks if there was no error. Otherwise, logs error in
     * STDOUT.
     *
     * @param  string  $migration  migration name
     * @return bool
     * @throws Exception
     */
    private function storeMigration(string $migration): bool
    {
        $result = Application::get('database')
            ->insertToDB(
                Application::get('config')['database']['db'] . '.migrations',
                ['migration_name' => $migration]
            );
        if (!key_exists('error', $result)) {
            return true;
        } else {
            print_r($result);
            return false;
        }
    }


    /**
     * Delete migration from 'migrations' table in DB. Checks if there was no error. Otherwise, logs error in STDOUT.
     *
     * @param  string  $migration  migration name
     * @return bool
     * @throws Exception
     */
    public function deleteMigration(string $migration): bool
    {
        $result = Application::get('database')
            ->delete(
                Application::get('config')['database']['db'] . '.migrations',
                "migration_name",
                ['\'' . $migration . '\'']
            );
        if (!key_exists('error', $result)) {
            return true;
        } else {
            print_r($result);
            return false;
        }
    }
}