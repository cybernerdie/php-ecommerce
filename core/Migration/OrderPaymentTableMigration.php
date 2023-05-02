<?php

namespace Core\Migration;

use Core\Interfaces\MigrationInterface;
use Illuminate\Database\Capsule\Manager as DB;

class OrderPaymentTableMigration implements MigrationInterface
{
    public function run()
    {
        $schema = DB::schema();

        if (!$schema->hasTable('order_payments')) {
            $schema->create('order_payments', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('order_id');
                $table->string('payment_method');
                $table->float('amount');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        
                $table->foreign('order_id')->references('id')->on('orders');
            });
        }
    }
}