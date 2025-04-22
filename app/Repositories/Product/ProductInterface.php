<?php

namespace App\Repositories\Product;

use App\Enums\Constant;
use App\Repositories\BaseRepositoryInterface;

interface ProductInterface extends BaseRepositoryInterface
{
    public function listProduct();
    public function paginateByCategoryId($id,$perPage = Constant::PER_PAGE);
}
