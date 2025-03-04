<?php

namespace App\Http\Controllers\Api\App;

use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
  protected $orderService;
  //fix
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

      $order = $this->orderService->createOrder($userId, $request->items);
      return response()->json([
        'message' => 'Đặt hàng thành công!',
        'order_id' => $order->id
      ], 201);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }
  /**
   * @OA\Get(
   *     path="/api/app/orders/show",
   *     summary="Lấy danh sách đơn hàng của người dùng hiện tại",
   *     description="Trả về danh sách đơn hàng của người dùng đang đăng nhập dựa trên token.",
   *     tags={"APP"},
   *     security={{"bearerAuth":{}}},
   *     @OA\Response(
   *         response=200,
   *         description="Danh sách đơn hàng của người dùng",
   *         @OA\JsonContent(
   *             type="array",
   *             @OA\Items(
   *                 type="object",
   *                 @OA\Property(property="id", type="integer", description="ID đơn hàng"),
   *                 @OA\Property(property="user_id", type="integer", description="ID người dùng"),
   *                 @OA\Property(property="total_price", type="string", description="Tổng giá trị đơn hàng"),
   *                 @OA\Property(property="status", type="string", description="Trạng thái đơn hàng"),
   *                 @OA\Property(property="created_at", type="string", format="date-time", description="Thời gian tạo đơn hàng"),
   *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Thời gian cập nhật đơn hàng")
   *             )
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Chưa xác thực (token không hợp lệ hoặc thiếu)",
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Không tìm thấy đơn hàng",
   *     )
   * )
   */
  public function show(Request $request)
  {
    try {
      // Lấy user_id từ user đang đăng nhập
      $authUserId = auth()->id();

      // Gọi service để lấy đơn hàng của người dùng
      $orders = $this->orderService->getOrdersByUserID($authUserId);

      // Kiểm tra nếu không có đơn hàng
      if ($orders->isEmpty()) {
        return response()->json(['message' => 'Đơn hàng chưa có sản phẩm'], Response::HTTP_BAD_REQUEST);
      }

      // Trả về danh sách đơn hàng của người dùng
      return response()->json([
        'message' => 'Đơn hàng của bạn',
        'orders' => $orders
      ]);
    } catch (\Exception $e) {
      // Xử lý khi có lỗi
      return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
  /**
   * @OA\Get(
   *     path="/api/app/orders/show/{id}",
   *     summary="Lấy chi tiết đơn hàng của người dùng",
   *     description="Trả về chi tiết đơn hàng của người dùng.",
   *     tags={"APP"},
   *     security={{"bearerAuth":{}}},
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         description="ID của đơn hàng",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Chi tiết đơn hàng của người dùng",
   *         @OA\JsonContent(
   *             type="array",
   *             @OA\Items(
   *                 type="object",
   *                 @OA\Property(property="id", type="integer", description="ID đơn hàng"),
   *                 @OA\Property(property="user_id", type="integer", description="ID người dùng"),
   *                 @OA\Property(property="total_price", type="string", description="Tổng giá trị đơn hàng"),
   *                 @OA\Property(property="status", type="string", description="Trạng thái đơn hàng"),
   *                 @OA\Property(property="created_at", type="string", format="date-time", description="Thời gian tạo đơn hàng"),
   *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Thời gian cập nhật đơn hàng")
   *             )
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Chưa xác thực (token không hợp lệ hoặc thiếu)",
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Không tìm thấy đơn hàng",
   *     )
   * )
   */
  public function getOrderDetails($orderId)
  {
    try {
      $orderDetails = $this->orderService->getOrderDetails($orderId);

      return response()->json([
        'message' => 'Chi tiết sản phẩm trong đơn hàng của bạn',
        'details' => OrderResource::collection($orderDetails),
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $e) {
      throw new HttpResponseException(response()->json([
        'message' => 'Không tìm thấy đơn hàng.',
      ], Response::HTTP_NOT_FOUND));
    }
  }
  /**
   * @OA\Put(
   *     path="/api/app/orders/update/{id}",
   *     summary="Cập nhật thông tin đơn hàng",
   *     security={{"bearerAuth":{}}},
   *     tags={"APP"},
   *     description="Cập nhật thông tin đơn hàng dựa trên ID",
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         description="ID của đơn hàng",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"items"},
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
   *         response=200,
   *         description="Đơn hàng đã được cập nhật thành công",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Đơn hàng đã được cập nhật thành công")
   *         )
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Không tìm thấy đơn hàng",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Không tìm thấy đơn hàng")
   *         )
   *     ),
   *     @OA\Response(
   *         response=400,
   *         description="Dữ liệu không hợp lệ",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Dữ liệu không hợp lệ")
   *         )
   *     )
   * )
   */
  public function update(Request $request, $orderId)
  {
    // Validate các trường thông tin đầu vào
    $request->validate([
      'items' => 'required|array', // Đảm bảo items là mảng
      'items.*.product_id' => 'required|integer|min:1|exists:products,id', // Kiểm tra sản phẩm tồn tại trong bảng products
      'items.*.quantity' => 'required|integer|min:1', // Kiểm tra số lượng hợp lệ cho từng sản phẩm
    ]);

    // Truyền userId (auth()->id()) và items từ request vào phương thức updateOrder
    $order = $this->orderService->updateOrder(auth()->id(), $orderId, $request->items);

    return response()->json([
      'message' => 'Đơn hàng đã được cập nhật thành công',
      'order' => $order
    ], Response::HTTP_OK);
  }
  /**
   * @OA\Delete(
   *     path="/api/app/orders/delete/{id}",
   *     tags={"APP"},
   *     summary="Xóa đơn hàng",
   *     security={{"bearerAuth":{}}},
   *     description="Xóa đơn hàng khỏi hệ thống",
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         description="ID của đơn hàng",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="đơn hàng đã được xoá",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="đơn hàng đã được xoá thành công")
   *         )
   *     ),
   *     @OA\Response(response=404, description="Không tìm thấy đơn hàng")
   * )
   */
  public function destroy($orderId)
  {
    $this->orderService->deleteOrder($orderId);

    return response()->json(['message' => 'Đơn hàng đã được xoá thành công'], Response::HTTP_OK);
  }
}
