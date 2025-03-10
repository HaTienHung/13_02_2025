<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepositoryInterface;

interface CategoryInterface extends BaseRepositoryInterface
{
  public function getProductsByCategory($categoryId);
  public function findByName($name);
}
