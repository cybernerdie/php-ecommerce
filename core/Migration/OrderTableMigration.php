<?php

namespace Core\Migration;

use Core\Interfaces\MigrationInterface;
use Illuminate\Database\Capsule\Manager as DB;

class OrderTableMigration implements MigrationInterface
{
    public function run()
    {
        $schema = DB::schema();

        if (!$schema->hasTable('orders')) {
            $schema->create('orders', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('total_amount');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }
}