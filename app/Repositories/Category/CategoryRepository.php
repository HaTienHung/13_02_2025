<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }
    // public function getProductsByCategory($categoryId)
    // {
    //   return $this->model->where('id', $categoryId)
    //     ->whereHas('products') // Chỉ lấy danh mục có sản phẩm
    //     ->with('products')
    //     ->get();
    //   //Lấy tất cả các sản phẩm của danh mục !!!
    // }
    // Có thể thêm các phương thức đặc biệt riêng cho Product nếu cần
    // public function findByName($name)
    // {
    //   return $this->model->where('name', $name)->first();
    // }
}
