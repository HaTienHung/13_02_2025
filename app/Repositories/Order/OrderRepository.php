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

  // Có thể thêm các phương thức đặc biệt riêng cho Product nếu cần
}
