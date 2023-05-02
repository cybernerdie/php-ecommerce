<?php

use App\Core\App;
use App\Core\Database\Connection;
use Core\Migration\DatabaseTableMigration;
use Core\Seeder\SeedProductData;

App::bind('config.database', require 'config/database.php');

$config = App::get('config.database');
$databaseConfiguration =  $config['connections']['mysql'];
$connection = (new Connection($databaseConfiguration))->boot();

(new DatabaseTableMigration())->run();
(new SeedProductData())->run();

