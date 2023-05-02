<?php

namespace Core\Migration;

use Core\Interfaces\MigrationInterface;
use Illuminate\Database\Capsule\Manager as DB;

class TokenTableMigration implements MigrationInterface
{
    public function run()
    {
        $schema = DB::schema();

        if (!$schema->hasTable('tokens')) {
            $schema->create('tokens', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->string('token');
                $table->dateTime('expiry');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }
}