<?php

namespace App\Services\Cart;

use App\Repositories\Cart\CartInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Order\OrderService;


class CartService
{
  protected $cartRepository;
  protected $orderService;

  public function __construct(CartInterface $cartRepository, OrderService $orderService)
  {
    $this->cartRepository = $cartRepository;
    $this->orderService = $orderService;
  }

  public function getCart($userId)
  {
    return $this->cartRepository->getCartItems($userId);
  }

  public function addItemToCart($userId, $productId, $quantity)
  {
    // Kiểm tra xem sản phẩm có trong giỏ hàng chưa
    $cartItem = $this->cartRepository->findOneBy([
      'user_id' => $userId,
      'product_id' => $productId
    ]);

    if ($cartItem) {
      // Nếu đã tồn tại, cập nhật số lượng
      $newQuantity = $cartItem->quantity + $quantity;
      return $this->cartRepository->updateCartItem($cartItem, $newQuantity);
    }

    // Nếu chưa có, thêm mới vào giỏ hàng
    return $this->cartRepository->createCartItem($userId, $productId, $quantity);
  }

  public function updateCartItem($userId, $productId, $quantity)
  {
    $cartItem = $this->cartRepository->findOneBy([
      'user_id' => $userId,
      'product_id' => $productId
    ]);
    if (!$cartItem) {
      throw new ModelNotFoundException("Sản phẩm không có trong giỏ hàng.");
    }

    return $this->cartRepository->updateCartItem($cartItem, $quantity);
    return $cartItem;
  }


  public function removeCartItems($userId, array $productIds)
  {
    // Lọc ra những sản phẩm thực sự có trong giỏ hàng
    $cartItems = $this->cartRepository->findBy([
      'user_id' => $userId,
      'product_id' => $productIds
    ]);

    if ($cartItems->isEmpty()) {
      throw new ModelNotFoundException("Xoá thất bại: Không có sản phẩm nào trong giỏ hàng.");
    }

    // Xóa các sản phẩm có trong danh sách
    return $this->cartRepository->removeCartItems($userId, $productIds);
  }

  public function clearCart($userId)
  {
    return $this->cartRepository->clearCart($userId);
  }

  public function checkout($userId, array $productIds)
  {
    $cartItems = $this->cartRepository->getCartItems($userId);

    if ($cartItems->isEmpty()) {
      throw new \Exception("Giỏ hàng trống.");
    }

    // Lọc ra các sản phẩm được đặt hàng
    $itemsToOrder = $cartItems->whereIn('product_id', $productIds);

    if ($itemsToOrder->isEmpty()) {
      throw new \Exception("Không có sản phẩm hợp lệ để đặt hàng.");
    }

    $order = $this->orderService->createOrder($userId, $itemsToOrder);

    // Xóa chỉ những sản phẩm đã đặt khỏi giỏ hàng
    $this->cartRepository->removeCartItems($userId, $productIds);

    return $order;
  }
}
