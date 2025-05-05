<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create multiple products using the Product model
        Product::create([
            'name' => 'Product 1',
            'description' => 'Description of Product 1',
            'price' => 100.00,
            'stock' => 50,
        ]);

        Product::create([
            'name' => 'Product 2',
            'description' => 'Description of Product 2',
            'price' => 150.00,
            'stock' => 30,
        ]);

        Product::create([
            'name' => 'Product 3',
            'description' => 'Description of Product 3',
            'price' => 200.00,
            'stock' => 20,
        ]);
    }
}
