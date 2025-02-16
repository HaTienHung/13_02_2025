<?php

namespace App\Repositories\Inventory;

use App\Models\InventoryTransaction;

class InventoryRepository
{
  public function addStock($productId, $quantity)
  {
    return InventoryTransaction::create([
      'product_id' => $productId,
      'type' => 'import',
      'quantity' => $quantity
    ]);
  }

  public function reduceStock($productId, $quantity)
  {
    return InventoryTransaction::create([
      'product_id' => $productId,
      'type' => 'export',
      'quantity' => $quantity,
    ]);
  }

  public function getStock($productId)
  {
    $imported = InventoryTransaction::where('product_id', $productId)
      ->where('type', 'import')
      ->sum('quantity');

    $exported = InventoryTransaction::where('product_id', $productId)
      ->where('type', 'export')
      ->sum('quantity');

    return $imported - $exported;
  }
}
