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

  public function getCartItems($userId)
  {
    return $this->model->where('user_id', $userId)->with('product')->get();
  }

  public function createCartItem($userId, $productId, $quantity)
  {
    return $this->model->create([
      'user_id' => $userId,
      'product_id' => $productId,
      'quantity' => $quantity
    ]);
  }

  public function updateCartItem($cartItem, $quantity)
  {
    return $cartItem->update(['quantity' => $quantity]);
  }

  public function removeCartItems($userId, array $productIds)
  {
    return $this->model->where('user_id', $userId)->whereIn('product_id', $productIds)->delete();
  }

  public function clearCart($userId)
  {
    return $this->model->where('user_id', $userId)->delete();
  }
}
