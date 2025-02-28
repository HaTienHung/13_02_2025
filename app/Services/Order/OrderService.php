<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Repositories\Order\OrderInterface;
use App\Repositories\Product\ProductInterface;
use App\Repositories\OrderItem\OrderItemInterface;
use App\Repositories\Inventory\InventoryRepository;
use App\Services\Inventory\InventoryService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderService
{
  protected $productRepository;
  protected $orderRepository;
  protected $orderItemRepository;
  protected $inventoryRepository;
  protected $inventoryService;

  /**
   * Khởi tạo OrderService.
   *
   * @param OrderRepositoryInterface $orderRepository
   * @param ProductRepositoryInterface $productRepository
   * @param OrderItemInterface $orderItemRepository
   * @param InventoryRepository $inventoryRepository
   * @param InventoryService $inventorySerivice
   */

  public function __construct(
    OrderInterface $orderRepository,
    ProductInterface $productRepository,
    OrderItemInterface $orderItemRepository,
    InventoryService $inventoryService
  ) {
    $this->orderRepository = $orderRepository;
    $this->productRepository = $productRepository;
    $this->orderItemRepository = $orderItemRepository;
    $this->inventoryService = $inventoryService;
  }

  public function getAllOrders() //Only Admin
  {
    return $this->orderRepository->all();
  }

  public function getOrderDetails($orderId)
  {
    $order = $this->orderRepository->find($orderId);

    // Kiểm tra quyền truy cập
    if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
      throw new \Exception("Bạn không có quyền xem đơn hàng này.", Response::HTTP_FORBIDDEN);
    }

    return $this->orderRepository->getOrderDetails($order->id);
  }

  public function getOrdersByUserID($userId)
  {
    // Gọi repository để lấy đơn hàng của người dùng
    return $this->orderRepository->getOrdersByUserID($userId);
  }

  public function createOrder($userId, $items)
  {
    DB::beginTransaction();
    try {
      // Tạo đơn hàng mới
      $order = $this->orderRepository->create([
        'user_id' => $userId,
        'status' => 'pending',
        'total_price' => 0
      ]);

      $totalPrice = 0;

      foreach ($items as $item) {
        $product = $this->productRepository->find($item['product_id']);

        $stock = $this->inventoryService->getStock($product->id);
        if ($stock < $item['quantity']) {
          throw new \Exception("Sản phẩm {$product->name} không đủ hàng trong kho.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Giảm số lượng tồn kho
        $this->inventoryService->reduceStock($product->id, $item['quantity']);

        $itemTotal = $product->price * $item['quantity'];
        $totalPrice += $itemTotal;

        // Thêm sản phẩm vào OrderItem
        $this->orderItemRepository->create([
          'order_id' => $order->id,
          'product_id' => $product->id,
          'quantity' => $item['quantity'],
          'price' => $product->price
        ]);
      }

      // Cập nhật tổng giá trị đơn hàng
      $this->orderRepository->update($order->id, ['total_price' => $totalPrice]);

      DB::commit();
      return $order;
    } catch (\Exception $e) {
      DB::rollBack();
      throw new \Exception($e->getMessage());
    }
  }

  public function updateOrder($userId, $orderId, $items, $status = null)
  {
    DB::beginTransaction();
    try {
      // Tìm đơn hàng cần cập nhật
      $order = $this->orderRepository->find($orderId);

      // Kiểm tra quyền: Chỉ user sở hữu đơn hàng mới có quyền cập nhật
      if ($order->user_id !== $userId && !auth()->user()->isAdmin()) {
        throw new \Exception("Bạn không có quyền sửa đơn hàng này.", Response::HTTP_FORBIDDEN);
      }

      // Kiểm tra trạng thái đơn hàng (chỉ cho phép sửa khi đơn hàng đang 'pending')
      if ($order->status !== 'pending' && !auth()->user()->isAdmin()) {
        throw new \Exception("Chỉ có thể sửa đơn hàng khi đang ở trạng thái 'pending'.", Response::HTTP_FORBIDDEN);
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
            $stock = $this->inventoryService->getStock($productId);
            if ($stock < $difference) {
              throw new \Exception("Sản phẩm {$product->name} không đủ hàng trong kho.", Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $this->inventoryService->reduceStock($productId, $difference);
          } elseif ($difference < 0) {
            // Nếu số lượng giảm, hoàn lại hàng vào kho
            $this->inventoryService->addStock($productId, abs($difference));
          }

          // Cập nhật order item
          $this->orderItemRepository->update($existingItem->id, [
            'quantity' => $newQuantity,
            'price' => $product->price
          ]);
        } else {
          // Nếu sản phẩm chưa có, thêm mới vào order_items
          $stock = $this->inventoryService->getStock($productId);
          if ($stock < $newQuantity) {
            throw new \Exception("Sản phẩm {$product->name} không đủ hàng trong kho.", Response::HTTP_UNPROCESSABLE_ENTITY);
          }
          $this->inventoryService->reduceStock($productId, $newQuantity);

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
          $this->inventoryService->addStock($existingItem->product_id, $existingItem->quantity);
          $this->orderItemRepository->delete($existingItem->id);
        }
      }

      // Cập nhật tổng giá trị đơn hàng
      $updateData = ['total_price' => $totalPrice];

      // Nếu admin gửi trạng thái mới, cập nhật luôn
      if ($status !== null) {
        $updateData['status'] = $status;
      }

      $this->orderRepository->update($order->id, $updateData);
      DB::commit();
      $order = $this->orderRepository->find($order->id);
      return $order;
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      DB::rollBack();
      throw new \Exception("Không tìm thấy đơn hàng.", Response::HTTP_NOT_FOUND);
    }
  }

  public function deleteOrder($orderId)
  {
    DB::beginTransaction();
    try {
      // Lấy thông tin đơn hàng, nếu không tìm thấy sẽ ném ModelNotFoundException
      $order = $this->orderRepository->find($orderId);

      // Kiểm tra quyền: chỉ user sở hữu hoặc admin mới có quyền xoá
      if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
        throw new \Exception("Bạn không có quyền xoá đơn hàng này.", Response::HTTP_FORBIDDEN);
      }

      // Lấy danh sách sản phẩm trong đơn hàng
      $orderItems = $this->orderItemRepository->getItemsByOrderId($orderId);

      // Khôi phục lại số lượng sản phẩm trong kho trước khi xoá
      foreach ($orderItems as $item) {
        $this->inventoryService->addStock($item->product_id, $item->quantity);
      }

      // Xoá tất cả order items liên quan đến đơn hàng
      $this->orderItemRepository->deleteByOrderId($orderId);

      // Xoá đơn hàng
      $this->orderRepository->delete($orderId);

      DB::commit();
      return true;
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      DB::rollBack();
      throw new \Exception("Không tìm thấy đơn hàng.", Response::HTTP_NOT_FOUND);
    }
  }
}
