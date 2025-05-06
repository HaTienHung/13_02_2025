<?php

namespace App\Services\Cart;

use App\Repositories\Cart\CartInterface;
use App\Services\Order\OrderService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;


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
    public function updateMultipleItems(array $cartItems, int $userId): array
    {
        DB::beginTransaction();
        try {
            foreach ($cartItems as $item) {
                $this->cartRepository->createOrUpdate(
                    $item,
                    [['user_id', '=', $userId], ['product_id', '=', $item['product_id']]]
                );
            }

            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'message' => trans('messages.success.cart.update')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}
