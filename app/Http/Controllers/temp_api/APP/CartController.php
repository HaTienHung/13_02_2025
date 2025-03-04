<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Cart",
 *     description="Quản lý giỏ hàng của người dùng"
 * )
 */
class CartController extends Controller
{
  protected $cartService;
  /**
   * Khởi tạo CartController.
   * 
   * @param CartService $cartService
   */

  public function __construct(CartService $cartService)
  {
    $this->cartService = $cartService;
  }

  /**
   * Lấy giỏ hàng của người dùng
   * 
   * @OA\Get(
   *     path="/api/app/cart",
   *     summary="Lấy giỏ hàng",
   *     tags={"Cart"},
   *     security={{"bearerAuth":{}}},
   *     @OA\Response(response=200, description="Danh sách sản phẩm trong giỏ hàng"),
   *     @OA\Response(response=401, description="Chưa xác thực")
   * )
   */
  public function getCart()
  {
    $cart = $this->cartService->getCart(auth()->id());
    return response()->json(['cart' => $cart], Response::HTTP_OK);
  }

  /**
   * Thêm sản phẩm vào giỏ hàng
   * 
   * @OA\Post(
   *     path="/api/app/cart/store",
   *     summary="Thêm sản phẩm vào giỏ hàng",
   *     tags={"Cart"},
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

  public function addToCart(Request $request)
  {
    $request->validate([
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|integer|min:1'
    ]);

    $this->cartService->addItemToCart(auth()->id(), $request->product_id, $request->quantity);

    return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng.'], Response::HTTP_OK);
  }

  /**
   * Cập nhật số lượng sản phẩm trong giỏ hàng
   * 
   * @OA\Put(
   *     path="/api/app/cart/update",
   *     summary="Cập nhật giỏ hàng",
   *     tags={"Cart"},
   *     security={{"bearerAuth":{}}},
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"product_id", "quantity"},
   *             @OA\Property(property="product_id", type="integer", example=1),
   *             @OA\Property(property="quantity", type="integer", example=3)
   *         )
   *     ),
   *     @OA\Response(response=200, description="Cập nhật số lượng thành công"),
   *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
   *     @OA\Response(response=401, description="Chưa xác thực")
   * )
   */
  public function updateCart(Request $request)
  {
    $request->validate([
      'product_id' => [
        'required',
        'integer',
        Rule::exists('cart_items', 'product_id')->where('user_id', auth()->id())
      ],
      'quantity' => 'required|integer|min:1'
    ]);

    $updatedCartItem = $this->cartService->updateCartItem(auth()->id(), $request->product_id, $request->quantity);

    if (!$updatedCartItem) {
      return response()->json([
        'message' => 'Cập nhật thất bại.',
      ], Response::HTTP_BAD_REQUEST);
    }

    return response()->json([
      'message' => 'Cập nhật giỏ hàng thành công.',
      'updated' => $updatedCartItem
    ], Response::HTTP_OK);
  }
  /**
   * @OA\Delete(
   *     path="/api/app/cart/delete",
   *     summary="Xóa nhiều sản phẩm khỏi giỏ hàng",
   *     description="Xóa một hoặc nhiều sản phẩm khỏi giỏ hàng của người dùng đã đăng nhập.",
   *     tags={"Cart"},
   *     security={{ "bearerAuth":{} }},
   * 
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"product_ids"},
   *             @OA\Property(
   *                 property="product_ids",
   *                 type="array",
   *                 @OA\Items(type="integer"),
   *                 example={1, 2, 3}
   *             )
   *         )
   *     ),
   * 
   *     @OA\Response(
   *         response=200,
   *         description="Xóa sản phẩm thành công",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Xóa sản phẩm thành công."),
   *             @OA\Property(property="deleted_count", type="integer", example=3)
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
  public function removeCartItems(Request $request)
  {
    $request->validate([
      'product_ids' => 'required|array',
      'product_ids.*' => [
        'integer',
        Rule::exists('cart_items', 'product_id')->where('user_id', auth()->id())
      ]
    ]);

    $this->cartService->removeCartItems(auth()->id(), $request->product_ids);

    return response()->json(['message' => 'Xoa san pham thanh cong'], Response::HTTP_OK);
  }

  /**
   * Xóa toàn bộ giỏ hàng
   * 
   * @OA\Delete(
   *     path="/api/app/cart/clear",
   *     summary="Dọn sạch giỏ hàng",
   *     tags={"Cart"},
   *     security={{"bearerAuth":{}}},
   *     @OA\Response(response=200, description="Giỏ hàng đã được dọn sạch"),
   *     @OA\Response(response=401, description="Chưa xác thực")
   * )
   */
  public function clearCart()
  {
    $this->cartService->clearCart(auth()->id());
    return response()->json(['message' => 'Giỏ hàng đã được dọn sạch.'], Response::HTTP_OK);
  }
  /**
   * @OA\Post(
   *     path="/api/app/cart/checkout",
   *     summary="Đặt hàng từ giỏ hàng",
   *     description="Người dùng có thể đặt hàng các sản phẩm từ giỏ hàng của mình.",
   *     tags={"Cart"},
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
  public function checkout(Request $request)
  {
    $request->validate([
      'product_ids' => 'required|array',
      'product_ids.*' => [
        'integer',
        Rule::exists('cart_items', 'product_id')->where('user_id', auth()->id())
      ]
    ]);

    $order = $this->cartService->checkout(auth()->id(), $request->product_ids);

    return response()->json(['message' => 'Đặt hàng thành công', 'order' => $order], Response::HTTP_CREATED);
  }
}
