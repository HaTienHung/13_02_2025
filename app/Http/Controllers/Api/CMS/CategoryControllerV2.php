<?php

namespace App\Http\Controllers\Api\CMS;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryInterface;
use App\Services\Category\CategoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="CMS Categories",
 * )
 */
class CategoryControllerV2 extends Controller
{
    protected CategoryService $categoryService;
    protected CategoryInterface $categoryRepository;

    /**
     * Khởi tạo CategoryControllerV2.
     *
     * @param CategoryService $categoryService
     * @param CategoryInterface $categoryRepository
     */

    public function __construct(CategoryService $categoryService, CategoryInterface $categoryRepository)
    {
        $this->categoryService = $categoryService;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/cms/categories",
     *     security={{"bearerAuth":{}}},
     *     tags={"CMS Categories"},
     *     summary="Lấy ra danh sách danh mục",
     *     @OA\Response(
     *         response=200,
     *         description="A list of products",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Category"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->all();
        return response()->json([
            'status' => Constant::SUCCESS_CODE,
            'message' => 'Danh sách các danh mục',
            'data' => $categories
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/cms/categories/{id}/products",
     *     tags={"CMS Categories"},
     *     security={{"bearerAuth":{}}},
     *     summary="Lấy danh sách sản phẩm dựa vào Danh mục",
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
    public function getProductsByCategory($categoryId): JsonResponse
    {
        try {
            $products = $this->categoryService->getProductsByCategory($categoryId);
            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Danh sách các sản phẩm của danh mục',
                'data' => $products
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/cms/categories/create",
     *     tags={"CMS Categories"},
     *     summary="Tạo danh mục mới",
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
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = $this->categoryService->createCategory($request->all());

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('message.success.category.create'),
                'category' => $category
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
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
    public function update(Request $request, $categoryId): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
            ]);

            $category = $this->categoryService->updateCategory($categoryId, $request->all());

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('message.success.category.update'),
                'data' => $category
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
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
    public function destroy($id): JsonResponse
    {
        try {
            $this->categoryService->deleteCategory($id);

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('message.success.category.delete')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
