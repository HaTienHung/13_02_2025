<?php

namespace App\Http\Controllers\Api\App;

use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class OrderController extends Controller
{
  protected $orderService;

  /**
   * Khởi tạo OrderController.
   * 
   * @param OrderService $orderService
   */

  public function __construct(OrderService $orderService)
  {
    $this->orderService = $orderService;
  }
  /**
   * Tạo đơn hàng mới.
   *
   * @OA\Post(
   *     path="/api/app/orders/create",
   *     summary="Người dùng đặt hàng",
   *     description="Tạo một đơn hàng với danh sách sản phẩm và số lượng.",
   *     tags={"APP"},
   *     security={{"bearerAuth":{}}},
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"user_id", "items"},
   *             @OA\Property(
   *                 property="items",
   *                 type="array",
   *                 description="Danh sách sản phẩm trong đơn hàng",
   *                 @OA\Items(
   *                     type="object",
   *                     @OA\Property(property="product_id", type="integer", example=5, description="ID sản phẩm"),
   *                     @OA\Property(property="quantity", type="integer", example=2, description="Số lượng sản phẩm")
   *                 )
   *             )
   *         )
   *     ),
   *     @OA\Response(
   *         response=201,
   *         description="Đặt hàng thành công",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Đặt hàng thành công!"),
   *             @OA\Property(property="order_id", type="integer", example=123)
   *         )
   *     ),
   *     @OA\Response(
   *         response=400,
   *         description="Lỗi dữ liệu hoặc sản phẩm không đủ hàng",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Sản phẩm không đủ hàng trong kho.")
   *         )
   *     ),
   * )
   *
   * @param Request $request
   * @return JsonResponse
   */

  public function store(Request $request) //Phai fix lai
  {
    $request->validate([
      'items' => 'required|array|min:1',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|integer|min:1'
    ]);

    try {
      // Lấy user_id từ request hiện tại
      $userId = $request->user()->id;

      $order = $this->orderService->placeOrder($userId, $request->items);
      return response()->json([
        'message' => 'Đặt hàng thành công!',
        'order_id' => $order->id
      ], 201);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 400);
    }
  }
}
