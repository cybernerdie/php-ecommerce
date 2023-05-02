<?php

namespace Core\Migration;

use Core\Interfaces\MigrationInterface;
use Illuminate\Database\Capsule\Manager as DB;

class OrderItemTableMigration implements MigrationInterface
{
    public function run()
    {
        $schema = DB::schema();

        if (!$schema->hasTable('order_items')) {
            $schema->create('order_items', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('order_id');
                $table->unsignedInteger('product_id');
                $table->unsignedInteger('quantity');
                $table->unsignedInteger('price');
                $table->unsignedInteger('total_amount');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
    }
}