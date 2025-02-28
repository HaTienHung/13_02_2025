<?php

namespace App\Http\Controllers\Api\Cms;

use App\Services\Product\ProductService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="Swagger API Documentation",
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */

class ProductController extends Controller
{
  protected $productService;
  /**
   * Khởi tạo ProductController.
   * 
   * @param ProductService $productService
   */

  public function __construct(ProductService $productService)
  {
    $this->productService = $productService;
  }
  /**
   * @OA\Get(
   *     path="/api/products",
   *     tags={"APP"},
   *     summary="Get list of products",
   *     @OA\Response(
   *         response=200,
   *         description="A list of products",
   *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
   *     )
   * )
   */
  public function index()
  {
    return response()->json($this->productService->getAllProducts(), Response::HTTP_OK);
  }
  /**
   * @OA\Get(
   *     path="/api/products/{id}",
   *     tags={"APP"},
   *     summary="Get product by ID",
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         description="ID của sản phẩm",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="A product detail",
   *         @OA\JsonContent(ref="#/components/schemas/Product")
   *     )
   * )
   */

  public function show($id)
  {
    try {
      $product = $this->productService->getProductById($id);
      return response()->json([
        'message' => 'Thông tin sản phẩm',
        'product' => $product,
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Không tìm thấy sản phẩm.',
      ], Response::HTTP_NOT_FOUND);
    }
  }

  /**
   * @OA\Post(
   *     path="/api/cms/product/create",
   *     tags={"CMS Products"},
   *     summary="Create a new product",
   *     security={{"bearerAuth":{}}},
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/Product")
   *     ),
   *     @OA\Response(
   *         response=201,
   *         description="Product created",
   *         @OA\JsonContent(ref="#/components/schemas/Product")
   *     )
   * )
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'price' => 'required|numeric|min:0',
      'category_id' => 'required|integer|min:0|exists:categories,id'
    ]);

    return response()->json(['messsage' => 'Sản phẩm đã được thêm thành công', 'product' => $this->productService->createProduct($request->all())], Response::HTTP_CREATED);
  }
  /**
   * @OA\Put(
   *     path="/api/cms/product/update/{id}",
   *     summary="Cập nhật sản phẩm",
   *     security={{"bearerAuth":{}}},
   *     tags={"CMS Products"},
   *     description="Cập nhật thông tin sản phẩm dựa trên ID",
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         description="ID của sản phẩm",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"name","price","category_id"},
   *             @OA\Property(property="name", type="string", example=".."),
   *             @OA\Property(property="price", type="number", format="float", example=40),
   *             @OA\Property(property="category_id", type="number", example=1),
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Sản phẩm đã được cập nhật",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Sản phẩm đã được cập nhật thành công")
   *         )
   *     ),
   *     @OA\Response(response=404, description="Không tìm thấy sản phẩm"),
   *     @OA\Response(response=400, description="Dữ liệu không hợp lệ")
   * )
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|string',
      'price' => 'required|numeric',
      'category_id' => 'required|integer|exists:categories,id'
    ]);

    try {
      $product = $this->productService->updateProduct($id, $request->all());

      return response()->json([
        'message' => 'Sản phẩm đã được cập nhật thành công',
        'product' => $product
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $e) {
      return response()->json(['message' => 'Không tìm thấy sản phẩm'], Response::HTTP_NOT_FOUND);
    }
  }

  /**
   * @OA\Delete(
   *     path="/api/cms/product/delete/{id}",
   *     tags={"CMS Products"},
   *     summary="Xóa sản phẩm",
   *     security={{"bearerAuth":{}}},
   *     description="Xóa sản phẩm khỏi hệ thống",
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         description="ID của sản phẩm",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Sản phẩm đã được xoá",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Sản phẩm đã được xoá thành công")
   *         )
   *     ),
   *     @OA\Response(response=404, description="Không tìm thấy sản phẩm")
   * )
   */
  public function destroy($id)
  {
    try {
      $this->productService->deleteProduct($id);
      return response()->json(['message' => 'Sản phẩm đã được xóa thành công'], Response::HTTP_OK);
    } catch (ModelNotFoundException $e) {
      return response()->json(['message' => 'Sản phẩm không tồn tại'], Response::HTTP_NOT_FOUND);
    }
  }
}
