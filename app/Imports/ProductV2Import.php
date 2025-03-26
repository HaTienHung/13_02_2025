<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductV2Import implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Product::create([
                'name' => $row['name'],// Cột 'name' trong CSV
                'description' => $row['description'],
                'price' => $row['price'],
                'category_id' => $row['category_id'],
            ]);
        }
    }
}
