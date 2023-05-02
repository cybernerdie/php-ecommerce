<?php

namespace Core\Seeder;

use Illuminate\Database\Capsule\Manager as DB;
use DateTime;

class SeedProductData 
{
    public function run()
    {
        $productsTable = DB::schema()->hasTable('products');
    
        if (!$productsTable) return;
    
        $products = DB::table('products')->get();
    
        if ($products->count() > 0) return;
    
        if (!file_exists('products.csv')) return;
    
        $file = fopen("products.csv", "r");
        $headers = fgetcsv($file);
    
        while (($data = fgetcsv($file)) !== FALSE) {
            $product = array_combine($headers, $data);
    
            $dateTime = DateTime::createFromFormat('d/m/Y H:i:s', $product['date_added']);
            $date = $dateTime !== false ? $dateTime->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
    
            DB::table('products')->insert([
                'name' => $product['name'],
                'barcode' => $product['barcode'],
                'brand' => $product['brand'],
                'price' => $product['price'] * 100,
                'image_url' => $product['image_url'],
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }
    
        fclose($file);
    }
}