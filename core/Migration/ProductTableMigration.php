<?php

namespace Core\Migration;

use Core\Interfaces\MigrationInterface;
use Illuminate\Database\Capsule\Manager as DB;

class ProductTableMigration implements MigrationInterface
{
    public function run()
    {
        $schema = DB::schema();

        if (!$schema->hasTable('products')) {
            $schema->create('products', function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->string('barcode')->unique();
                $table->string('brand');
                $table->unsignedInteger('price');
                $table->string('image_url');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
    }
}