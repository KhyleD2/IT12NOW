<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Pomelo', 'price' => 50.00],
            ['name' => 'Durian', 'price' => 150.00],
            ['name' => 'Mango', 'price' => 80.00],
            ['name' => 'Banana', 'price' => 30.00],
        ];

        foreach($products as $product) {
            Product::create($product);
        }
    }
}
