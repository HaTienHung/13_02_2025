<?php

namespace App\Services\Category;

use App\Enums\Constant;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Product\ProductInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{
    protected CategoryInterface $categoryRepository;
    protected ProductInterface $productRepository;

    public function __construct(CategoryInterface $categoryRepository, ProductInterface $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    public function updateCategory($id, array $data)
    {
        $this->categoryRepository->find($id);

        // Kiểm tra xem có danh mục khác đã tồn tại với cùng tên hay không
        $existingCategory = $this->categoryRepository->findBy('name', $data['name']);

        //Nếu danh mục đã tồn tại và ID danh mục này khác với danh mục cần sửa thì không cho sửa
        if ($existingCategory && $existingCategory->id !== $id) {
            throw new Exception(trans('messages.errors.category.exists'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory($id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            // Kiểm tra xem danh mục có sản phẩm không
            if ($category->products()->exists()) {
                throw new Exception(trans('messages.errors.category.cannot_delete'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            // Thực hiện xóa danh mục
            if (!$this->categoryRepository->delete($id)) {
                throw new Exception(trans('messages.errors.category.delete'), Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (ModelNotFoundException $e) {
            throw new Exception(trans('messages.errors.not_found'), Response::HTTP_NOT_FOUND);
        }
    }

    public function createCategory(array $data)
    {
        // Kiểm tra xem danh mục đã tồn tại chưa
        $existingCategory = $this->categoryRepository->findBy('name', $data['name']);

        if ($existingCategory) {
            throw new Exception(trans('messages.errors.category.exists'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->categoryRepository->create($data);
    }

    public function getProductsByCategory($categoryId)
    {
        try {
            return  $this->categoryRepository->findById($categoryId, ['products']);
        } catch (ModelNotFoundException $e) {
            throw new Exception(trans('messages.errors.not_found'), Response::HTTP_NOT_FOUND);
        }
    }
    public function getProductsByCategorySlug($slug, $perPage = Constant::PER_PAGE)
    {
        try {

            //            return $this->categoryRepository->findAllBy(['slug'=>$slug],['products']);
            $category = $this->categoryRepository->findBy('slug', $slug);
            return $this->productRepository->paginateByCategoryId($category->id, $perPage);
        } catch (ModelNotFoundException $e) {
            throw new Exception(trans('messages.errors.not_found'), Response::HTTP_NOT_FOUND);
        }
    }
}
