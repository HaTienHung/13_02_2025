<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductInterface;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

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
    return $this->productRepository->delete($id);
  }
}
