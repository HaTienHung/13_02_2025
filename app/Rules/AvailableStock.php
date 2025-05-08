<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Services\Inventory\InventoryService;

class AvailableStock implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $productId;
    protected $inventoryService;

    public function __construct($productId, InventoryService $inventoryService)
    {
        $this->productId = $productId;
        $this->inventoryService = $inventoryService;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $stock = $this->inventoryService->getStock($this->productId);
        if ($value > $stock) {
            $fail("Chỉ có {$stock} sản phẩm có thể thêm vào giỏ hàng do giới hạn số lượng còn lại.");
        }
    }
}
