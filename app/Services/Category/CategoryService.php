<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

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
    $this->categoryRepository->find($id);

    // Kiểm tra xem có danh mục khác đã tồn tại với cùng tên hay không
    $existingCategory = $this->categoryRepository->findByName($data['name']);

    //Nếu danh mục đã tồn tại và ID danh mục này khác với danh mục cần sửa thì không cho sửa
    if ($existingCategory && $existingCategory->id !== $id) {
      throw new \Exception("Tên danh mục đã tồn tại", Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    return $this->categoryRepository->update($id, $data);
  }

  public function deleteCategory($id)
  {
    $category = $this->categoryRepository->find($id);

    // Kiểm tra xem danh mục có sản phẩm không
    if ($category->products()->exists()) {
      throw new Exception('Không thể xóa danh mục vì vẫn còn sản phẩm', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Thực hiện xóa danh mục
    if (!$this->categoryRepository->delete($id)) {
      throw new Exception('Xóa danh mục thất bại', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    return true;
  }
  public function createCategory(array $data)
  {
    // Kiểm tra xem danh mục đã tồn tại chưa
    $existingCategory = $this->categoryRepository->findByName($data['name']);

    if ($existingCategory) {
      throw new \Exception("Tên danh mục đã tồn tại", Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    return $this->categoryRepository->create($data);
  }

  public function getProductsByCategory($categoryId)
  {
    $this->categoryRepository->find($categoryId);
    $products = $this->categoryRepository->getProductsByCategory($categoryId);

    if ($products->isEmpty()) {
      throw new \Exception("Không có sản phẩm nào thuộc danh mục này !!!", Response::HTTP_NOT_FOUND);
    }
    return $products;
  }
}
