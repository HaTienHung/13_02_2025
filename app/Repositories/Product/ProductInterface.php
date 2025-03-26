<?php

namespace App\Repositories\Product;

use App\Repositories\BaseRepositoryInterface;

interface ProductInterface extends BaseRepositoryInterface
{
    public function listProduct();
}
