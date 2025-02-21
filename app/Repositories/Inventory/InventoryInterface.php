<?php

namespace App\Repositories\Inventory;

use App\Repositories\BaseRepositoryInterface;

interface InventoryInterface extends BaseRepositoryInterface
{
  public function addStock($productId, $quantity);
  public function reduceStock($productId, $quantity);
  public function getStock($productId);
  public function getImportProduct($productId);
  public function getExportProduct($productId);
}
