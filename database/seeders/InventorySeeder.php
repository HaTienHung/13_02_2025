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
            'product_id' => 3,
            'quantity' => '50',
            'type' => 'import'
        ]);
        InventoryTransaction::create([
            'product_id' => 4,
            'quantity' => '50',
            'type' => 'import'
        ]);
        InventoryTransaction::create([
            'product_id' => 5,
            'quantity' => '50',
            'type' => 'import'
        ]);
    }
}
