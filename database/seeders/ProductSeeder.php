<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::truncate();

        Product::create(['name' => 'Shirt', 'price' => 299, 'stock_quantity' => 10]);
        Product::create(['name' => 'Jens', 'price' => 599, 'stock_quantity' => 15]);
        Product::create(['name' => 'Shoes', 'price' => 799, 'stock_quantity' => 20]);
    }
}
