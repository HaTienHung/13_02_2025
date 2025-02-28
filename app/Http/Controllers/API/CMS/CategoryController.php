<?php

namespace App\Http\Controllers\Api\Cms;

use App\Services\Category\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
  protected $categoryService;

  /**
   * Khởi tạo CategoryController.
   * 
   * @param CategoryService $categoryService
   */

  public function __construct(CategoryService $categoryService)
  {
    $this->categoryService = $categoryService;
  }
  /**
   * @OA\Get(
   *     path="/api/categories",
   *     tags={"APP"},
   *     summary="Get list of categories",
   *     @OA\Response(
   *         response=200,
   *         description="A list of products",
   *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Category"))
   *     )
   * )
   */
  public function index()
  {
    $categories = $this->categoryService->getAllCategories();
    return response()->json(['message' => 'Danh sách các danh mục', 'categories' => $categories], Response::HTTP_OK);
  }
  /**
   * @OA\Get(
   *     path="/api/categories/{id}/products",
   *     tags={"APP"},
   *     summary="Get products by Category",
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         description="ID của danh mục",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="A product detail",
   *         @OA\JsonContent(ref="#/components/schemas/Category")
   *     )
   * )
   */
  public function getProductsByCategory($categoryId)
  {
    try {
      $products = $this->categoryService->getProductsByCategory($categoryId);
      return response()->json([
        'message' => 'Danh sách các sản phẩm của danh mục',
        'products' => $products
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $e) {
      return response()->json(['message' => "Danh mục không tồn tại"], Response::HTTP_NOT_FOUND);
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage()
      ], Response::HTTP_UNPROCESSABLE_ENTITY); // Trả về lỗi 404 nếu danh mục không có sản phẩm
    }
  }
  /**
   * @OA\Post(
   *     path="/api/cms/categories/create",
   *     tags={"CMS Categories"},
   *     summary="Create a new category",
   *     security={{"bearerAuth":{}}},
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/Category")
   *     ),
   *     @OA\Response(
   *         response=201,
   *         description="Category created",
   *         @OA\JsonContent(ref="#/components/schemas/Category")
   *     )
   * )
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
    ]);

    try {
      $category = $this->categoryService->createCategory($request->all());

      return response()->json([
        'message' => 'Danh mục đã được thêm thành công',
        'category' => $category
      ], Response::HTTP_CREATED);
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage()
      ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  /**
   * @OA\Put(
   *     path="/api/cms/categories/update/{id}",
   *     summary="Cập nhật danh mục",
   *     security={{"bearerAuth":{}}},
   *     tags={"CMS Categories"},
   *     description="Cập nhật thông tin danh mục dựa trên ID",
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         description="ID của danh mục",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"name","price","category_id"},
   *             @OA\Property(property="name", type="string", example=".."),
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="danh mục đã được cập nhật",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="danh mục đã được cập nhật thành công")
   *         )
   *     ),
   *     @OA\Response(response=404, description="Không tìm thấy danh mục"),
   *     @OA\Response(response=400, description="Dữ liệu không hợp lệ")
   * )
   */
  public function update(Request $request, $categoryId)
  {
    $request->validate([
      'name' => 'required|string',
    ]);
    try {
      $category = $this->categoryService->updateCategory($categoryId, $request->all());

      return response()->json([
        'message' => 'Danh mục đã được cập nhật thành công',
        'category' => $category
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $e) {
      return response()->json(['message' => 'Danh mục không tồn tại'], Response::HTTP_NOT_FOUND);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }
  /**
   * @OA\Delete(
   *     path="/api/cms/categories/delete/{id}",
   *     tags={"CMS Categories"},
   *     summary="Xóa danh mục",
   *     security={{"bearerAuth":{}}},
   *     description="Xóa danh mục khỏi hệ thống",
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         description="ID của danh mục",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="danh mục đã được xoá",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="danh mục đã được xoá thành công")
   *         )
   *     ),
   *     @OA\Response(response=404, description="Không tìm thấy danh mục")
   * )
   */
  public function destroy($id)
  {
    try {
      $this->categoryService->deleteCategory($id);
      return response()->json(['message' => 'Danh mục đã được xóa thành công'], 200);
    } catch (ModelNotFoundException $e) {
      return response()->json(['message' => 'Danh mục không tồn tại'], 404);
    }
  }
}
