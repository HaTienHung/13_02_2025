<?php

namespace App\Repositories\Cart;

use App\Models\CartItem;
use App\Repositories\BaseRepository;

class CartRepository extends BaseRepository implements CartInterface
{
    public function __construct(CartItem $cartItem)
    {
        parent::__construct($cartItem);
    }

}
