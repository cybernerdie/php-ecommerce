<?php

namespace Core\Migration;

use Core\Interfaces\MigrationInterface;
use Illuminate\Database\Capsule\Manager as DB;

class UserTableMigration implements MigrationInterface
{
    public function run()
    {
        $schema = DB::schema();

        if (!$schema->hasTable('users')) {
            $schema->create('users', function ($table) {
                $table->increments('id');
                $table->string('name')->nullable(false);
                $table->string('email')->unique()->nullable(false);
                $table->string('password')->nullable(false);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
    }
}