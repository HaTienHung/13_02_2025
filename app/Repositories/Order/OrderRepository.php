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
    public function listOrder()
    {
        $orders = $this->model->with('orderItems.product')->search(request('searchFields'), request('search'))
            ->filter(request('filter'))
            ->sort(request('sort'))->get();
        return $orders;
    }
}
