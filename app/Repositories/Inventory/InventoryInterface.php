<?php

namespace App\Repositories\Inventory;

use App\Repositories\BaseRepositoryInterface;

interface InventoryInterface extends BaseRepositoryInterface
{
  public function getImportProduct($productId);
  public function getExportProduct($productId);
  public function showInventoryRecords($productId);
}
