<?php

namespace App\Services\Cart;

use App\Repositories\Cart\CartInterface;
use App\Services\Order\OrderService;


class CartService
{
    protected CartInterface $cartRepository;
    protected OrderService $orderService;

    public function __construct(CartInterface $cartRepository, OrderService $orderService)
    {
        $this->cartRepository = $cartRepository;
        $this->orderService = $orderService;
    }

    public function checkout($userId, array $productIds)
    {
        $itemsToOrder = $this->cartRepository->findAllBy([
            ['user_id', '=', auth()->id()],
            ['product_id', 'in', $productIds]
        ]);

        $order = $this->orderService->createOrder($userId, $itemsToOrder);

        // Xóa các sản phẩm đã đặt khỏi giỏ hàng
        $this->cartRepository->deleteBy([
            ['user_id', '=', $userId],
            ['product_id', 'in', $productIds]
        ]);

        return $order;
    }
}
