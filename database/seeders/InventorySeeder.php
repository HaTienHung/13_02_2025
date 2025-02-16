<?php

namespace Database\Seeders;

use App\Models\InventoryTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        InventoryTransaction::create([
            'product_id' => '1',
            'stock' => '50',
            'type' => 'import'
        ]);
        InventoryTransaction::create([
            'product_id' => '2',
            'stock' => '50',
            'type' => 'import'
        ]);
        InventoryTransaction::create([
            'product_id' => '3',
            'stock' => '50',
            'type' => 'import'
        ]);
    }
}
