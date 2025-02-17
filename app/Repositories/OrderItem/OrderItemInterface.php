<?php

namespace App\Repositories\OrderItem;

use App\Repositories\BaseRepositoryInterface;

interface OrderItemInterface extends BaseRepositoryInterface
{
  public function getItemsByOrderID($orderID);
  public function deleteByOrderID($orderID);
}
