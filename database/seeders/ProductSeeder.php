<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Product::create([
            'name' => 'Grape',
            'description' => '',
            'price' => 120,
            'category_id' => '1',
        ]);
        Product::create([
            'name' => 'Watermelon',
            'description' => '',
            'price' => 50,
            'category_id' => '1',
        ]);
        Product::create([
            'name' => 'CocaCola',
            'description' => '',
            'price' => 25,
            'category_id' => '2',
        ]);
        Product::create([
            'name' => 'Fanta',
            'description' => '',
            'price' => 25,
            'category_id' => '2',
        ]);
    }
}
