<?php

namespace App\Repositories\Order;

use App\Repositories\BaseRepositoryInterface;

interface OrderInterface extends BaseRepositoryInterface
{
    public function listOrder();
}
