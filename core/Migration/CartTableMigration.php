<?php

namespace Core\Migration;

use Core\Interfaces\MigrationInterface;
use Illuminate\Database\Capsule\Manager as DB;

class CartTableMigration implements MigrationInterface
{
    public function run()
    {
        $schema = DB::schema();

        if (!$schema->hasTable('carts')) {
            $schema->create('carts', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('product_id');
                $table->unsignedInteger('quantity')->default(1);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('product_id')->references('id')->on('products');
            });
        }
    }
}