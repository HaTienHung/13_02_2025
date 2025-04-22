<?php

namespace App\Repositories\Inventory;

use App\Enums\Constant;
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
    public function listInventory($perpage = Constant::PER_PAGE)
    {
        $inventories = $this->model->search(request('searchFields'), request('search'))
            ->filter(request('filter'))
            ->sort(request('sort'))->paginate($perpage);
        return $inventories;
    }
}
