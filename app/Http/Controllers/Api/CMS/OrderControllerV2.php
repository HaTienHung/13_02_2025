<?php

namespace App\Http\Controllers\Api\CMS;

use App\Enums\Constant;
use App\Exports\OrderV2Export;
use App\Http\Controllers\Controller;
use App\Http\Requests\CMS\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Order\OrderRepository;
use App\Services\Order\OrderService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @OA\Tag(
 *     name="CMS Orders",
 * )
 */

class OrderControllerV2 extends Controller
{
    protected OrderService $orderService;
    protected OrderRepository $orderRepository;

    /**
     * Khởi tạo OrderController.
     *
     * @param OrderService $orderService
     * @param OrderRepository $orderRepository
     */

    public function __construct(OrderService $orderService, OrderRepository $orderRepository)
    {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/cms/orders/",
     *     tags={"CMS Orders"},
     *     summary="Lấy ra danh sách tất cả đơn hàng",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *           in="query",
     *           name="searchFields[]",
     *           required=false,
     *           description="List of fields to search. Example: ['name_booking']",
     *          @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *               type="string",
     *               example="name_booking"
     *              )
     *           ),
     *      ),
     *     @OA\Parameter(
     *            in="query",
     *            name="search",
     *            required=false,
     *            description="Content search. Example: 'Thiên'",
     *            @OA\Schema(
     *              type="string",
     *              example="Thiên",
     *            ),
     *       ),
     *     @OA\Parameter(
     *             in="query",
     *             name="filter",
     *             required=false,
     *             description="Filter criteria in JSON format. Example: {""created_at_RANGE"": [""2024-01-20"", ""2024-01-28""]}",
     *             @OA\Schema(
     *              type="string",
     *              example="{""created_at_RANGE"": [""2024-01-20"", ""2024-01-28""]}",
     *            ),
     *        ),
     *     @OA\Parameter(
     *              in="query",
     *              name="sort[]",
     *              required=false,
     *              description="Sort criteria in array format. Use '-' for descending order and '+' for ascending order. Example: ['-created_at']",
     *              @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *               type="string",
     *               example="-created_at"
     *              )
     *           ),
     *         ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *             @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Success."),
     *          )
     *     ),
     * )
     */

    public function index(): JsonResponse
    {
        $orders = $this->orderRepository->listOrder();
        return response()->json(['message' => 'Danh sách đơn hàng:', 'data' => OrderResource::collection($orders)], Response::HTTP_OK);
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
    public function getOrderDetails($id): JsonResponse
    {
        try {
            $orderDetails = $this->orderRepository->findById($id, ['orderItems']);

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Chi tiết sản phẩm trong đơn hàng của bạn',
                'data' => new OrderResource($orderDetails)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) { //FirstOrFail tu dong nem ra ModelNotFound
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => trans('messages.errors.not_found')
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
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

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|unique:users,email',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1'
            ]);

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
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('messages.success.order.create'),
                'order_id' => $order->id
            ], Response::HTTP_CREATED);
        } catch (Throwable $th) {
            // Nếu có lỗi, rollback lại tất cả thay đổi (bao gồm xóa user)
            DB::rollBack();
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $th->getMessage()
            ], Response::HTTP_NOT_FOUND);
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
     *             required={ "status"},
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

    public function update(OrderRequest $request, $id): JsonResponse
    {
        // Truyền userId (auth()->id()) và items từ request vào phương thức updateOrder
        try {
            // Validate các trường thông tin đầu vào
            $request->validate([
                'status' => 'required|in:completed,cancelled',
                //                'items' => 'required|array', // Đảm bảo items là mảng
                //                'items.*.product_id' => 'required|integer|min:1|exists:products,id', // Kiểm tra sản phẩm tồn tại trong bảng products
                //                'items.*.quantity' => 'required|integer|min:1', // Kiểm tra số lượng hợp lệ cho từng sản phẩm
            ]);

            $order = $this->orderRepository->createOrUpdate($request->all(), ['id' => $id]);

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('messages.success.order.update'),
                'data' => new OrderResource($order)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
                'data' => []
            ], Response::HTTP_BAD_REQUEST);
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
    public function destroy($id): JsonResponse
    {
        try {
            $this->orderService->deleteOrder($id);

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('messages.success.order.delete')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Get (
     *     path="/api/cms/orders/export-excel",
     *     tags={"CMS Orders"},
     *     summary="Xuất Excel",
     *     security={{"bearerAuth":{}}},
     *     operationId="order/export-excel",
     *     @OA\Parameter(
     *           in="query",
     *           name="searchFields[]",
     *           required=false,
     *           description="List of fields to search. Example: ['name']",
     *          @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *               type="string",
     *               example="name"
     *              )
     *           ),
     *      ),
     *     @OA\Parameter(
     *            in="query",
     *            name="search",
     *            required=false,
     *            description="Content search. Example: 'Tin tuc'",
     *            @OA\Schema(
     *              type="string",
     *              example="Tin tuc",
     *            ),
     *       ),
     *     @OA\Parameter(
     *             in="query",
     *             name="filter",
     *             required=false,
     *             description="Filter criteria in JSON format. Example: {""created_at_RANGE"": [""2024-01-20"", ""2024-01-28""]}",
     *             @OA\Schema(
     *              type="string",
     *              example="{""created_at_RANGE"": [""2024-01-20"", ""2024-01-28""]}",
     *            ),
     *        ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *             @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Success."),
     *          )
     *     ),
     * )
     */
    public function exportExcel()
    {
        try {
            $orders = $this->orderRepository->listOrder();
            //            return response()->json(['orders' => $orders]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $th->getMessage(),
                'data' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return Excel::download(
            new OrderV2Export($orders),
            'Order' . str_replace('/', '-', date('Y/m/d')) . '.xlsx'
        );
    }
}
