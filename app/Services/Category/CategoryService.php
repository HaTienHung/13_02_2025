<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryInterface;
use Exception;

class CategoryService
{
  protected $categoryRepository;

  public function __construct(CategoryInterface $categoryRepository)
  {
    $this->categoryRepository = $categoryRepository;
  }
  public function getAllCategories()
  {
    return $this->categoryRepository->all();
  }
  public function getCategoryByID($id)
  {
    return $this->categoryRepository->find($id);
  }
  public function updateCategory($id, array $data)
  {
    $category = $this->categoryRepository->find($id);
    if (!$category) {
      throw new Exception('Danh mục không tồn tại', 404);
    }

    if ($category['name'] === $data['name']) {
      throw new \Exception("Tên danh mục đã tồn tại");
    }
    return $this->categoryRepository->update($id, $data);
  }
  public function deleteCategory($id)
  {
    $category = $this->categoryRepository->find($id);

    if (!$category) {
      throw new Exception('Danh mục không tồn tại', 404);
    }

    // Kiểm tra xem danh mục có sản phẩm không
    if ($category->products()->exists()) {
      throw new Exception('Không thể xóa danh mục vì vẫn còn sản phẩm', 400);
    }

    // Thực hiện xóa danh mục
    if (!$this->categoryRepository->delete($id)) {
      throw new Exception('Xóa danh mục thất bại', 500);
    }

    return true;
  }
  public function createCategory(array $data)
  {
    return $this->categoryRepository->create($data);
  }
  public function getProductsByCategory($categoryId)
  {
    $products = $this->categoryRepository->getProductsByCategory($categoryId);
    if ($products->isEmpty()) {
      throw new \Exception("Không có sản phẩm nào thuộc danh mục này !!!");
    }
    return $products;
  }
}
