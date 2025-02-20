<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductInterface;
use Exception;

class ProductService
{
  protected $productRepository;

  public function __construct(ProductInterface $productRepository)
  {
    $this->productRepository = $productRepository;
  }

  public function getAllProducts()
  {
    return $this->productRepository->all();
  }

  public function getProductById($id)
  {
    return $this->productRepository->find($id);
  }

  public function createProduct(array $data)
  {
    return $this->productRepository->create($data);
  }

  public function updateProduct($id, array $data)
  {
    return $this->productRepository->update($id, $data);
  }

  public function deleteProduct($id)
  {
    $product = $this->productRepository->find($id);

    if (!$product) {
      throw new Exception('Sản phẩm không tồn tại', 404);
    }

    // Thực hiện xóa Sản phẩm
    if (!$this->productRepository->delete($id)) {
      throw new Exception('Xóa Sản phẩm thất bại', 500);
    }

    return true;
  }
}
