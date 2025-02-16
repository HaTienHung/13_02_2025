<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductInterface
{
  public function __construct(Product $product)
  {
    parent::__construct($product);
  }

  // Có thể thêm các phương thức đặc biệt riêng cho Product nếu cần
}
