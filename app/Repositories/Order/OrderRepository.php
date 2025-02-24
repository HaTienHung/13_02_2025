<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;

class OrderRepository extends BaseRepository implements OrderInterface
{
  public function __construct(Order $order)
  {
    parent::__construct($order);
  }
  public function getOrdersByUserID($userId)
  {
    return $this->model->where('user_id', $userId)->get(); //Lấy tất cả các đơn hàng của người dùng
  }
  public function getOrderDetails($orderId)
  {
    return $this->model->where('id', $orderId)
      ->with('orderItems.product') // Lấy luôn sản phẩm trong đơn
      ->get();
  }
}
