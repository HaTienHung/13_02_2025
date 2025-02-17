<?php

namespace App\Services\Order;

use App\Repositories\Order\OrderInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\OrderItem\OrderItemInterface;
use App\Repositories\Inventory\InventoryRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
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
  public function processOrder($userId, $items, $order = null)
  {
    DB::beginTransaction();
    try {
      // Nếu không phải sửa đơn hàng, tạo mới
      if (!$order) {
        $order = $this->orderRepository->create([
          'user_id' => $userId,
          'status' => 'pending',
          'total_price' => 0
        ]);
      }

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

        // Nếu sửa đơn hàng thì cần giảm bớt số lượng kho trước khi thay đổi
        if ($order->id) {
          $this->inventoryRepository->reduceStock($product['id'], $item['quantity']);
        }

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
  public function getAllOrders() //Only Admin
  {
    return $this->orderRepository->all();
  }

  public function getOrdersByUserID($userId)
  {
    // Gọi repository để lấy đơn hàng của người dùng
    return $this->orderRepository->getOrdersByUserID($userId);
  }
  public function createOrder($userId, $items)
  {
    return $this->processOrder($userId, $items);
  }

  public function updateOrder($userId, $orderId, $items)
  {
    DB::beginTransaction();
    try {
      // Tìm đơn hàng cần cập nhật
      $order = $this->orderRepository->find($orderId);

      if (!$order) {
        throw new \Exception("Đơn hàng không tồn tại.");
      }

      // Kiểm tra quyền: Chỉ user sở hữu đơn hàng mới có quyền cập nhật
      if ($order->user_id !== $userId) {
        throw new \Exception("Bạn không có quyền sửa đơn hàng này.");
      }

      $totalPrice = 0;
      $existingItems = $this->orderItemRepository->getItemsByOrderID($orderId);

      // Chuyển đổi danh sách sản phẩm hiện có thành mảng [product_id => quantity]
      $existingItemsMap = [];
      foreach ($existingItems as $item) {
        $existingItemsMap[$item->product_id] = $item;
      }

      foreach ($items as $item) {
        $product = $this->productRepository->find($item['product_id']);

        if (!$product) {
          throw new \Exception("Sản phẩm không tồn tại.");
        }

        $newQuantity = $item['quantity'];
        $productId = $product->id;
        $itemTotal = $product->price * $newQuantity;
        $totalPrice += $itemTotal;

        // Kiểm tra sản phẩm đã có trong đơn hàng chưa
        if (isset($existingItemsMap[$productId])) {
          // Nếu sản phẩm đã có, cập nhật số lượng
          $existingItem = $existingItemsMap[$productId];
          $oldQuantity = $existingItem->quantity;

          // Điều chỉnh kho dựa trên sự thay đổi số lượng
          $difference = $newQuantity - $oldQuantity;
          if ($difference > 0) {
            // Nếu số lượng tăng, kiểm tra kho
            $stock = $this->inventoryRepository->getStock($productId);
            if ($stock < $difference) {
              throw new \Exception("Sản phẩm {$product->name} không đủ hàng trong kho.");
            }
            $this->inventoryRepository->reduceStock($productId, $difference);
          } elseif ($difference < 0) {
            // Nếu số lượng giảm, hoàn lại hàng vào kho
            $this->inventoryRepository->addStock($productId, abs($difference));
          }

          // Cập nhật order item
          $this->orderItemRepository->update($existingItem->id, [
            'quantity' => $newQuantity,
            'price' => $product->price
          ]);
        } else {
          // Nếu sản phẩm chưa có, thêm mới vào order_items
          $stock = $this->inventoryRepository->getStock($productId);
          if ($stock < $newQuantity) {
            throw new \Exception("Sản phẩm {$product->name} không đủ hàng trong kho.");
          }
          $this->inventoryRepository->reduceStock($productId, $newQuantity);

          $this->orderItemRepository->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $newQuantity,
            'price' => $product->price
          ]);
        }
      }

      // Xóa những mục không còn trong danh sách cập nhật
      foreach ($existingItems as $existingItem) {
        if (!in_array($existingItem->product_id, array_column($items, 'product_id'))) {
          // Hoàn lại hàng vào kho trước khi xóa
          $this->inventoryRepository->addStock($existingItem->product_id, $existingItem->quantity);
          $this->orderItemRepository->delete($existingItem->id);
        }
      }

      // Cập nhật tổng giá trị đơn hàng
      $this->orderRepository->update($order->id, ['total_price' => $totalPrice]);
      $order = $this->orderRepository->find($order->id); // Load lại từ DB
      DB::commit();
      return $order;
    } catch (\Exception $e) {
      DB::rollBack();
      throw new \Exception($e->getMessage());
    }
  }
  public function deleteOrder($userId, $orderId)
  {
    DB::beginTransaction();
    try {
      // Lấy thông tin đơn hàng
      $order = $this->orderRepository->find($orderId);
      // Kiểm tra quyền: Chỉ user sở hữu đơn hàng mới có quyền cập nhật
      if ($order->user_id !== $userId) {
        throw new \Exception("Bạn không có quyền xoá đơn hàng này.");
      }

      if (!$order) {
        throw new \Exception("Đơn hàng không tồn tại.");
      }

      // Lấy danh sách sản phẩm trong đơn hàng
      $orderItems = $this->orderItemRepository->getItemsByOrderId($orderId);

      // Khôi phục lại số lượng sản phẩm trong kho trước khi xoá
      foreach ($orderItems as $item) {
        $this->inventoryRepository->addStock($item->product_id, $item->quantity);
      }

      // Xoá tất cả order items liên quan đến đơn hàng
      $this->orderItemRepository->deleteByOrderId($orderId);

      // Xoá đơn hàng
      $this->orderRepository->delete($orderId);

      DB::commit();
      return true;
    } catch (\Exception $e) {
      DB::rollBack();
      throw new \Exception($e->getMessage());
    }
  }
}
