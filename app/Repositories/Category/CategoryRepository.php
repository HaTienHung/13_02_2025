<?php

namespace App\Repositories\Category;

use App\Enums\Constant;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }
    public function listCategory($perpage = Constant::PER_PAGE)
    {
        $categories = $this->model->search(request('searchFields'), request('search'))
            ->filter(request('filter'))
            ->sort(request('sort'))->paginate($perpage);
        return $categories;
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
