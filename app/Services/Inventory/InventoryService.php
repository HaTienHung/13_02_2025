<?php

namespace App\Services\Inventory;

use App\Repositories\Inventory\InventoryRepository;
use App\Repositories\Product\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

use Exception;

class InventoryService
{
  protected $inventoryRepository;
  protected $productRepository;

  public function __construct(
    InventoryRepository $inventoryRepository,
    ProductRepository $productRepository
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
  public function getStock($productId)
  {
    // Kiểm tra sản phẩm có tồn tại không
    $this->productRepository->find($productId);
    $imported = $this->inventoryRepository->getImportProduct($productId);
    $exported = $this->inventoryRepository->getEXportProduct($productId);
    return $imported - $exported;
  }
  public function getStockReport()
  {
    return $this->productRepository->all()->map(function ($product) {
      return [
        'product_id' => $product->id,
        'product_name' => $product->name,
        'stock' => $this->getStock($product->id)
      ];
    });
  }
  public function showInventoryRecords($productId)
  {
    // Kiểm tra sản phẩm có tồn tại không
    $this->productRepository->find($productId);

    return $this->inventoryRepository->showInventoryRecords($productId);
  }
}
