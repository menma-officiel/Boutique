<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = \App\Models\Product::factory(12)->create();

        // Create some comments for each product
        $products->each(function ($product) {
            \App\Models\Comment::factory(rand(0, 3))->create(['product_id' => $product->id]);
        });

        // Some orders
        \App\Models\Order::factory(6)->create();
    }
}
