<?php

namespace App\Services\Inventory;

use App\Repositories\Inventory\InventoryRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;

class InventoryService
{
    protected InventoryRepository $inventoryRepository;
    protected ProductRepository $productRepository;

    public function __construct(
        InventoryRepository $inventoryRepository,
        ProductRepository   $productRepository
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->productRepository = $productRepository;
    }

    public function addStock($productId, $quantity)
    {
        // Ghi nhận giao dịch nhập kho
        return $this->inventoryRepository->create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'type' => 'import', // Loại giao dịch: nhập kho
        ]);
    }

    public function reduceStock($productId, $quantity)
    {
        return $this->inventoryRepository->create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'type' => 'export', // Loại giao dịch: xuat kho
        ]);
    }

    public function getStockReport()
    {
        // Lấy tất cả sản phẩm mà không phân trang
        $products = $this->productRepository->all();

        // Tiến hành transform lại dữ liệu
        $items = collect($products)->transform(function ($product) {
            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'stock' => $this->getStock($product->id)
            ];
        });

        return $items;
    }

    public function getStockReportWithPaginate()
    {
        $products = $this->productRepository->listProduct();

        $products->getCollection()->transform(function ($product) {
            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'stock' => $this->getStock($product->id)
            ];
        });

        return $products;
    }

    public function getStock($productId)
    {
        // Kiểm tra sản phẩm có tồn tại không
        $this->productRepository->find($productId);
        $imported = $this->inventoryRepository->getImportProduct($productId);
        $exported = $this->inventoryRepository->getEXportProduct($productId);
        return $imported - $exported;
    }
}
