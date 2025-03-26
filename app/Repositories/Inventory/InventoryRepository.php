<?php

namespace App\Repositories\Inventory;

use App\Models\InventoryTransaction;
use App\Repositories\BaseRepository;

class InventoryRepository extends BaseRepository implements InventoryInterface
{
    public function __construct(InventoryTransaction $inventoryTransaction)
    {
        parent::__construct($inventoryTransaction);
    }

    public function getImportProduct($productId)
    {

        return $this->findAllBy([['product_id', '=', $productId], ['type', '=', 'import']])->sum('quantity');
    }

    public function getExportProduct($productId)
    {

        return $this->findAllBy([['product_id', '=', $productId], ['type', '=', 'export']])->sum('quantity');
    }

}
