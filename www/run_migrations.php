<?php

use App\core\database\Migrations;

require __DIR__ . '/vendor/autoload.php';
require 'source/core/bootstrap.php';

$migrations = new Migrations();

$migrations->applyMigrations();
//$migrations->rollback();