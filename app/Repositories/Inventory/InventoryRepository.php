<?php

namespace App\Repositories\Inventory;

use App\Models\InventoryTransaction;
use App\Repositories\BaseRepository;

class InventoryRepository extends BaseRepository implements InventoryInterface
{
  public function __construct(InventoryTransaction $inventory)
  {
    parent::__construct($inventory);
  }
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
  public function getImportProduct($productId)
  {
    return $this->model->where('product_id', $productId)->where('type', 'import')->sum('quantity');
  }
  public function getExportProduct($productId)
  {
    return $this->model->where('product_id', $productId)->where('type', 'export')->sum('quantity');
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
