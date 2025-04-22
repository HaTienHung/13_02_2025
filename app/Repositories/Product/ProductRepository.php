<?php

namespace App\Repositories\Product;

use App\Enums\Constant;
use App\Models\Product;
use App\Repositories\BaseRepository;



class ProductRepository extends BaseRepository implements ProductInterface
{

    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    // Có thể thêm các phương thức đặc biệt riêng cho Product nếu cần
    public function listProduct($perpage = Constant::PER_PAGE)
    {
        $products = $this->model->with('category')->search(request('searchFields'), request('search'))
            ->filter(request('filter'))
            ->sort(request('sort'))->paginate($perpage);
        return $products;
    }
    public function getLastestProducts($limit=8)
    {
        return $this->model->orderBy('created_at', 'desc')->take($limit)->get();
    }
    public function paginateByCategoryId($id,$perPage = Constant::PER_PAGE)
    {
        $products = $this->model->where('category_id',$id)->latest()
            ->paginate($perPage);
        return $products;
    }
}
// $dependencies = $constructor->getParameters();
//
//        // Once we have all the constructor's parameters we can create each of the
//        // dependency instances and then use the reflection instances to make a
//        // new instance of this class, injecting the created dependencies in.
//        try {
//            $instances = $this->resolveDependencies($dependencies);
//        } catch (BindingResolutionException $e) {
//            array_pop($this->buildStack);
//
//            throw $e;
//        }
//
//        array_pop($this->buildStack);
//
//        return $reflector->newInstanceArgs($instances);

//try {
//            $instances = $this->resolveDependencies($dependencies);
//        } catch (BindingResolutionException $e) {
//            array_pop($this->buildStack);
//
//            throw $e;
//        }
//
//        array_pop($this->buildStack);
//
//        $this->fireAfterResolvingAttributeCallbacks(
//            $reflector->getAttributes(), $instance = $reflector->newInstanceArgs($instances)
//        );
//
//        return $instance;
