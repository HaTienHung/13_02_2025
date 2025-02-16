<?php

namespace App\Repositories\OrderItem;

use App\Models\OrderItem;
use App\Repositories\BaseRepository;

class OrderItemRepository extends BaseRepository implements OrderItemInterface
{
  public function __construct(OrderItem $orderItem)
  {
    parent::__construct($orderItem);
  }

  // Có thể thêm các phương thức đặc biệt riêng cho Product nếu cần
}
