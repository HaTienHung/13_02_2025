<?php

namespace App\Repositories\Cart;

use App\Repositories\BaseRepositoryInterface;

interface CartInterface extends BaseRepositoryInterface
{
  public function getCartItems($userId);
  public function createCartItem($userId, $productId, $quantity);
  public function updateCartItem($cartItem, $quantity);
  public function removeCartItems($userId, array $productIds);
  public function clearCart($userId);
}
