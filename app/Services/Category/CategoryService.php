<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{
    protected CategoryInterface $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function updateCategory($id, array $data)
    {
        $this->categoryRepository->find($id);

        // Kiểm tra xem có danh mục khác đã tồn tại với cùng tên hay không
        $existingCategory = $this->categoryRepository->findBy('name', $data['name']);

        //Nếu danh mục đã tồn tại và ID danh mục này khác với danh mục cần sửa thì không cho sửa
        if ($existingCategory && $existingCategory->id !== $id) {
            throw new Exception(trans('message.errors.category.exists'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory($id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            // Kiểm tra xem danh mục có sản phẩm không
            if ($category->products()->exists()) {
                throw new Exception(trans('message.errors.category.cannot_delete'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            // Thực hiện xóa danh mục
            if (!$this->categoryRepository->delete($id)) {
                throw new Exception(trans('message.errors.category.delete'), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (ModelNotFoundException $e) {
            throw new Exception(trans('message.errors.not_found'),Response::HTTP_NOT_FOUND);
        }

    }

    public function createCategory(array $data)
    {
        // Kiểm tra xem danh mục đã tồn tại chưa
        $existingCategory = $this->categoryRepository->findBy('name', $data['name']);

        if ($existingCategory) {
            throw new Exception(trans('message.errors.category.exists'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->categoryRepository->create($data);
    }

    public function getProductsByCategory($categoryId)
    {
        try {
            $category = $this->categoryRepository->findById($categoryId, ['products']);
//
//            if ($category && $category->products->isEmpty()) {
//                throw new Exception("Danh mục này không có sản phẩm nào !!!", Response::HTTP_NOT_FOUND);
//            }

            return $category;
        } catch (ModelNotFoundException $e) {
            throw new Exception(trans('message.errors.not_found'), Response::HTTP_NOT_FOUND);
        }
    }
}
