<?php

namespace App\Http\Controllers\Api\App;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryInterface;
use App\Services\Category\CategoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="APP Categories",
 * )
 */

class CategoryController extends Controller
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
     *     path="/api/categories",
     *     tags={"APP Categories"},
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
     *     path="/api/categories/{id}/products",
     *     tags={"APP Categories"},
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
     * @OA\Get(
     *     path="/api/categories/{slug}",
     *     tags={"APP Categories"},
     *     summary="Lấy danh sách sản phẩm dựa vào slug của danh mục",
     *     @OA\Parameter(
 *           in="query",
 *           name="page",
*            required=false,
*            description="Trang",
 *            @OA\Schema(
 *              type="integer",
 *              example=1,
 *            )
     *       ),
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Slug của danh mục",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách sản phẩm của danh mục",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy danh mục"
     *     )
     * )
     */
    public function getProductsByCategorySlug($slug): JsonResponse
    {
        try {
            $data = $this->categoryService->getProductsByCategorySlug($slug);
            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Danh sách các sản phẩm của danh mục',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
