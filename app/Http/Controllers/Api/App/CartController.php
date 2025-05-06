<?php

namespace App\Http\Controllers\Api\App;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Repositories\Cart\CartRepository;
use App\Services\Cart\CartService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="APP Cart",
 * )
 */

class CartController extends Controller
{
    protected CartService $cartService;
    protected CartRepository $cartRepository;

    /**
     * Khởi tạo CartController.
     *
     * @param CartService $cartService
     * @param CartRepository $cartRepository
     */

    public function __construct(CartService $cartService, CartRepository $cartRepository)
    {
        $this->cartService = $cartService;
        $this->cartRepository = $cartRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/app/cart",
     *     summary="Lấy giỏ hàng",
     *     tags={"APP Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Danh sách sản phẩm trong giỏ hàng"),
     *     @OA\Response(response=401, description="Chưa xác thực")
     * )
     */
    public function getCart(): JsonResponse
    {
        $cart = $this->cartRepository->findAllBy(['user_id' => auth()->id()], ['product']);

        return response()->json([
            'message' => trans('messages.success.success'),
            'data' => $cart
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/app/cart/store",
     *     summary="Thêm sản phẩm vào giỏ hàng",
     *     tags={"APP Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "quantity"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Sản phẩm đã được thêm vào giỏ hàng"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực")
     * )
     */

    public function addToCart(Request $request): JsonResponse
    {
        try {
            // Validate dữ liệu đầu vào
            $data = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            // Lấy ID người dùng hiện tại
            $data['user_id'] = auth()->id();

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng hay chưa
            $existingCartItem = $this->cartRepository->getFirstBy([
                ['user_id', '=', $data['user_id']],
                ['product_id', '=', $data['product_id']]
            ]);

            if ($existingCartItem) {
                // Nếu có, chỉ cần cập nhật số lượng
                $updatedQuantity = $existingCartItem->quantity + $data['quantity'];
                $this->cartRepository->update($existingCartItem->id, ['quantity' => $updatedQuantity]);

                return response()->json([
                    'status' => Constant::SUCCESS_CODE,
                    'message' => trans('messages.success.cart.store')
                ], Response::HTTP_OK);
            } else {
                // Nếu không, tạo mới mục giỏ hàng
                $this->cartRepository->createOrUpdate($data);

                return response()->json([
                    'status' => Constant::SUCCESS_CODE,
                    'message' => trans('messages.success.cart.create')
                ], Response::HTTP_CREATED);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/app/cart/update",
     *     summary="Cập nhật giỏ hàng",
     *     tags={"APP Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"items"},
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=3)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật số lượng thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực")
     * )
     */
    public function updateCart(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => [
                    'required',
                    'integer',
                    Rule::exists('cart_items', 'product_id')->where('user_id', auth()->id())
                ],
                'items.*.quantity' => 'required|integer|min:1'
            ]);

            $this->cartService->updateMultipleItems($data['items'], auth()->id());

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('messages.success.cart.update'),
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
                'data' => []
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    // /**
    //  * @OA\Delete(
    //  *     path="/api/app/cart/delete",
    //  *     summary="Xóa nhiều sản phẩm khỏi giỏ hàng",
    //  *     description="Xóa một hoặc nhiều sản phẩm khỏi giỏ hàng của người dùng đã đăng nhập.",
    //  *     tags={"APP Cart"},
    //  *     security={{ "bearerAuth":{} }},
    //  *
    //  *     @OA\RequestBody(
    //  *         required=true,
    //  *         @OA\JsonContent(
    //  *             required={"product_ids"},
    //  *             @OA\Property(
    //  *                 property="product_ids",
    //  *                 type="array",
    //  *                 @OA\Items(type="integer"),
    //  *                 example={1, 2, 3}
    //  *             )
    //  *         )
    //  *     ),
    //  *
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Xóa sản phẩm thành công",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="message", type="string", example="Xóa sản phẩm thành công."),
    //  *             @OA\Property(property="deleted_count", type="integer", example=3)
    //  *         )
    //  *     ),
    //  *
    //  *     @OA\Response(
    //  *         response=422,
    //  *         description="Dữ liệu không hợp lệ",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="message", type="string", example="The given data was invalid."),
    //  *             @OA\Property(property="errors", type="object")
    //  *         )
    //  *     ),
    //  *
    //  *     @OA\Response(
    //  *         response=401,
    //  *         description="Chưa đăng nhập",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="message", type="string", example="Unauthenticated.")
    //  *         )
    //  *     )
    //  * )
    //  */
    public function multipleDelete(Request $request): JsonResponse
    {

        try {
            $request->validate([
                'product_ids' => 'required|array',
                'product_ids.*' => [
                    'integer',
                    Rule::exists('cart_items', 'product_id')->where('user_id', auth()->id())
                ]
            ]);

            $this->cartRepository->deleteBy([['user_id', '=', auth()->id()], ['product_id', 'in', $request->product_ids]]);

            return response()->json(['message' => trans('messages.success.cart.delete')], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/app/cart/delete/{id}",
     *     summary="Xóa sản phẩm khỏi giỏ hàng",
     *     description="Xóa một sản phẩm khỏi giỏ hàng của người dùng đã đăng nhập.",
     *     tags={"APP Cart"},
     *     security={{ "bearerAuth":{} }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của sản phẩm cần xóa",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Xóa sản phẩm thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa sản phẩm thành công."),
     *             @OA\Property(property="deleted_count", type="integer", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Chưa đăng nhập",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    public function destroy($id): JsonResponse
    {
        try {
            // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng của người dùng không
            $product = $this->cartRepository->findAllBy([
                ['user_id', '=', auth()->id()],
                ['product_id', '=', $id]
            ]);

            // Nếu không tìm thấy sản phẩm trong giỏ hàng, trả về lỗi
            if ($product->isEmpty()) {
                return response()->json([
                    'status' => Constant::FALSE_CODE,
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng.',
                ], Response::HTTP_NOT_FOUND);
            }
            // Xóa sản phẩm theo id và user_id hiện tại
            $this->cartRepository->deleteBy([
                ['user_id', '=', auth()->id()],
                ['product_id', '=', $id]
            ]);

            return response()->json([
                'message' => trans('messages.success.cart.delete'),
                'id' => $product
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/app/cart/clear",
     *     summary="Dọn sạch giỏ hàng",
     *     tags={"APP Cart"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Giỏ hàng đã được dọn sạch"),
     *     @OA\Response(response=401, description="Chưa xác thực")
     * )
     */
    public function clearCart(): JsonResponse
    {
        $this->cartRepository->deleteBy(['user_id' => auth()->id()]);
        return response()->json([
            'status' => Constant::SUCCESS_CODE,
            'message' => trans('messages.success.cart.clear')
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/app/cart/checkout",
     *     summary="Đặt hàng từ giỏ hàng",
     *     description="Người dùng có thể đặt hàng các sản phẩm từ giỏ hàng của mình.",
     *     tags={"APP Cart"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Danh sách ID sản phẩm cần đặt hàng",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="product_ids",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1),
     *                 description="Mảng chứa ID của các sản phẩm cần đặt hàng"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Đặt hàng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Đặt hàng thành công"),
     *             @OA\Property(property="order", type="object", description="Thông tin đơn hàng")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Dữ liệu không hợp lệ."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Lỗi server",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Có lỗi xảy ra, vui lòng thử lại sau.")
     *         )
     *     )
     * )
     */
    public function checkout(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'product_ids' => 'required|array',
                'product_ids.*' => [
                    'integer',
                    Rule::exists('cart_items', 'product_id')->where('user_id', auth()->id())
                ]
            ]);

            $order = $this->cartService->checkout(auth()->id(), $request->product_ids);

            return response()->json(['message' => trans('messages.success.cart.checkout'), 'order' => $order], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
                'data' => []
            ], Constant::BAD_REQUEST_CODE);
        }
    }
}
