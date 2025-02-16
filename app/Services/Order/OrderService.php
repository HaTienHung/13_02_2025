<?php

namespace App\Services\Order;

use App\Repositories\Order\OrderInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\OrderItem\OrderItemInterface;
use App\Repositories\Inventory\InventoryRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
  protected $productRepository;
  protected $orderRepository;
  protected $orderItemRepository;
  protected $inventoryRepository;

  /**
   * Khởi tạo OrderService.
   *
   * @param OrderRepositoryInterface $orderRepository
   * @param ProductRepositoryInterface $productRepository
   * @param OrderItemInterface $orderItemRepository
   * @param InventoryRepository $inventoryRepository
   */
  public function __construct(
    OrderInterface $orderRepository,
    ProductInterface $productRepository,
    OrderItemInterface $orderItemRepository,
    InventoryRepository $inventoryRepository
  ) {
    $this->orderRepository = $orderRepository;
    $this->productRepository = $productRepository;
    $this->orderItemRepository = $orderItemRepository;
    $this->inventoryRepository = $inventoryRepository;
  }

  public function getAllOrders() //Only Admin
  {
    return $this->orderRepository->all();
  }

  public function getUserOrders($userId)
  {
    return $this->orderRepository->getUserOrders($userId); //Chua co repo
  }

  public function createOrder(array $data) //Only Admin
  {
    return $this->orderRepository->create($data);
  }

  public function updateOrder($orderID, array $data)
  {
    return $this->orderRepository->update($orderID, $data);
  }

  public function deleteOrder($orderID)
  {
    return $this->orderRepository->delete($orderID);
  }
  /**
   * Đặt hàng mới.
   *
   * @param int $userId ID của user đặt hàng.
   * @param array $items Danh sách sản phẩm [{ product_id, quantity }].
   * @return Order
   * @throws \Exception Nếu sản phẩm không đủ hàng hoặc lỗi khác.
   */
  public function placeOrder($userId, array $items)
  {
    DB::beginTransaction();
    try {
      $order = $this->orderRepository->create([
        'user_id' => $userId,
        'status' => 'pending',
        'total_price' => 0
      ]);

      $totalPrice = 0;

      foreach ($items as $item) {
        $product = $this->productRepository->find($item['product_id']);

        if (!$product) {
          throw new \Exception("Sản phẩm không tồn tại.");
        }
        $stock = $this->inventoryRepository->getStock($product['id']);
        if ($stock < $item['quantity']) {
          throw new \Exception("Sản phẩm {$product->name} không đủ hàng trong kho.");
        }
        $this->inventoryRepository->reduceStock($product['id'], $item['quantity']);
        $itemTotal = $product->price * $item['quantity'];
        $totalPrice += $itemTotal;

        $this->orderItemRepository->create([
          'order_id' => $order->id,
          'product_id' => $product->id,
          'quantity' => $item['quantity'],
          'price' => $product->price
        ]);
      }

      $this->orderRepository->update($order->id, ['total_price' => $totalPrice]);

      DB::commit();
      return $order;
    } catch (\Exception $e) {
      DB::rollBack();
      throw new \Exception($e->getMessage());
    }
  }
}
