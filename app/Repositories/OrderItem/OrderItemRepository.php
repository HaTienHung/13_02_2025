<?php

namespace App\Repositories\OrderItem;

use App\Models\CartItem;
use App\Models\OrderItem;
use App\Repositories\BaseRepository;

class OrderItemRepository extends BaseRepository implements OrderItemInterface
{
    public function __construct(OrderItem $orderItem)
    {
        parent::__construct($orderItem);
    }
    // public function getItemsByOrderId($orderId)
    // {
    //   return $this->model->where('order_id', $orderId)->get();
    // }
    // public function deleteByOrderId($orderId)
    // {
    //   return $this->model->where('order_id', $orderId)->delete();
    // }
}
