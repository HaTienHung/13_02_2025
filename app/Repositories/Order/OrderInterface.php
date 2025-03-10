<?php

namespace App\Repositories\Order;

use App\Repositories\BaseRepositoryInterface;

interface OrderInterface extends BaseRepositoryInterface
{
  public function getOrdersByUserID($userId);
  public function getOrderDetails($orderId);
}
