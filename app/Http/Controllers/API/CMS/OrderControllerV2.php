<?php

namespace App\Http\Controllers\Api\Cms;

use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

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
     *     tags={"CMS Order"},
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
     * Tạo đơn hàng mới.
     *
     * @OA\Post(
     *     path="/api/cms/orders/create",
     *     summary="Admin tạo đơn hàng",
     *     description="Tạo một đơn hàng với danh sách sản phẩm và số lượng.",
     *     tags={"CMS Order"},
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

        try {
            // Tạo user mới thay vì nhập user_id
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => bcrypt('password') // Tạo mật khẩu ngẫu nhiên
            ]);

            // Gọi service để xử lý đơn hàng
            $order = $this->orderService->placeOrder($user->id, $request->items);

            return response()->json([
                'message' => 'Admin tạo đơn hàng thành công!',
                'order_id' => $order->id
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
