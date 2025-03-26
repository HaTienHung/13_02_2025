<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Category::create([
            'name' => 'Fruits',
        ]);

        Category::create([
            'name' => 'Drinks',
        ]);

        Category::create([
            'name' => 'Snacks',
        ]);
    }
}
