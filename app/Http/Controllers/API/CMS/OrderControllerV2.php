<?php

namespace App\Http\Controllers\Api\Cms;

use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\OrderResource;

class OrderControllerV2 extends Controller
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
     * @OA\Get(
     *     path="/api/cms/orders/",
     *     tags={"CMS Orders"},
     *     summary="Get list of orders",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of orders",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Order"))
     *     )
     * )
     */

    public function index()
    {
        return response()->json($this->orderService->getAllOrders(), 200);
    }
    /**
     * @OA\Get(
     *     path="/api/cms/orders/show/{id}",
     *     summary="Lấy chi tiết đơn hàng của người dùng",
     *     description="Trả về chi tiết đơn hàng của người dùng.",
     *     tags={"CMS Orders"},
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
        $orderDetails = $this->orderService->getOrderDetails($orderId);

        return response()->json(['message' => 'Chi tiết sản phẩm trong đơn hàng của bạn', 'details' => OrderResource::collection($orderDetails)], 200);
        // return response()->json(['order' => $orderDetails]);
    }
    /**
     * Tạo đơn hàng mới.
     *
     * @OA\Post(
     *     path="/api/cms/orders/create",
     *     summary="Admin tạo đơn hàng",
     *     description="Tạo một đơn hàng với danh sách sản phẩm và số lượng.",
     *     tags={"CMS Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_name", "user_email", "items"},
     *             @OA\Property(
     *                 property="user_name",
     *                 type="string",
     *                 example="user_01",
     *                 description="Tên khách hàng"
     *             ),
     *             @OA\Property(
     *                 property="user_email",
     *                 type="string",
     *                 example="user_01@gmail.com",
     *                 description="Email khách hàng"
     *             ),
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
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            // Tạo user mới trong transaction
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => bcrypt('password') // Tạo mật khẩu mặc định
            ]);

            // Gọi service để xử lý đơn hàng
            $order = $this->orderService->createOrder($user->id, $request->items);

            // Nếu không lỗi, commit transaction
            DB::commit();

            return response()->json([
                'message' => 'Admin tạo đơn hàng thành công!',
                'order_id' => $order->id
            ], 201);
        } catch (\Exception $e) {
            // Nếu có lỗi, rollback lại tất cả thay đổi (bao gồm xóa user)
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/cms/orders/update/{id}",
     *     summary="Cập nhật thông tin đơn hàng",
     *     security={{"bearerAuth":{}}},
     *     tags={"CMS Orders"},
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
     *             required={"items", "status"},  
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 description="Danh sách sản phẩm trong đơn hàng",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", example=5, description="ID sản phẩm"),
     *                     @OA\Property(property="quantity", type="integer", example=2, description="Số lượng sản phẩm")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="pending",  
     *                 enum={"pending", "completed", "cancelled"},  
     *                 description="Trạng thái đơn hàng"
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
            'status' => 'required|in:pending,completed,cancelled',
            'items' => 'required|array', // Đảm bảo items là mảng
            'items.*.product_id' => 'required|integer|min:1|exists:products,id', // Kiểm tra sản phẩm tồn tại trong bảng products
            'items.*.quantity' => 'required|integer|min:1', // Kiểm tra số lượng hợp lệ cho từng sản phẩm
        ]);

        // Truyền userId (auth()->id()) và items từ request vào phương thức updateOrder
        try {
            $order = $this->orderService->updateOrder(auth()->id(), $orderId, $request->items, $request->status);

            return response()->json([
                'message' => 'Đơn hàng đã được cập nhật thành công',
                'order' => $order
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/cms/orders/delete/{id}",
     *     tags={"CMS Orders"},
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
        try {
            $this->orderService->deleteOrder($orderId);

            return response()->json(['message' => 'Đơn hàng đã được xoá thành công']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }
    }
}
