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
  public function getImportProduct($productId)
  {
    return $this->model->where('product_id', $productId)->where('type', 'import')->sum('quantity');
  }
  public function getExportProduct($productId)
  {
    return $this->model->where('product_id', $productId)->where('type', 'export')->sum('quantity');
  }
  public function showInventoryRecords($productId)
  {
    return $this->model->where('product_id', $productId)->get();
  }
}
