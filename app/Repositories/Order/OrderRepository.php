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
    return $this->model->where('user_id', $userId)->with('orderItems.product')->get(); //Lấy tất cả các đơn hàng của người dùng
  }
  // Có thể thêm các phương thức đặc biệt riêng cho Product nếu cần
}
