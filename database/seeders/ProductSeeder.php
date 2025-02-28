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
            'description' => 'Some thing aboud Grape',
            'price' => 150,
            'category_id' => 1,
        ]);
        Product::create([
            'name' => 'CocaCola',
            'description' => 'Some thing about CocaCola',
            'price' => 20,
            'category_id' => 2,
        ]);
        Product::create([
            'name' => 'Ostar',
            'description' => 'Some thing about Ostar',
            'price' => 10,
            'category_id' => 3,
        ]);
    }
}
