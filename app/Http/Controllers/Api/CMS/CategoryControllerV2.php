<?php

namespace App\Http\Controllers\Api\CMS;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\CMS\CategoryRequest;
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
     *     tags={"CMS Categories"},
     *     security={{"bearerAuth":{}}},
     *     summary="Lấy ra danh sách danh muc",
     *          @OA\Parameter(
     *           in="query",
     *           name="page",
     *           required=false,
     *           description="Trang",
     *           @OA\Schema(
     *             type="integer",
     *             example=1,
     *           )
     *      ),
     *      @OA\Parameter(
     *           in="query",
     *           name="perpage",
     *           required=false,
     *           description="Per Page",
     *           @OA\Schema(
     *             type="integer",
     *             example=10,
     *           )
     *      ),
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
        $categories = $this->categoryRepository->listCategory();
        return response()->json([
            'status' => Constant::SUCCESS_CODE,
            'message' => 'Danh sách các danh mục',
            'data' => $categories
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/cms/categories/{id}/",
     *     tags={"CMS Categories"},
     *     security={{"bearerAuth":{}}},
     *     summary="Lấy chi tiet Danh mục",
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
    public function getCategoryById($categoryId): JsonResponse
    {
        try {
            $category = $this->categoryRepository->find($categoryId);
            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Chi tiết danh mục',
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
    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|min:2',
                'slug' => 'nullable',
            ]);

            $category = $this->categoryService->createCategory($request->all());

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('messages.success.category.create'),
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
     *             required={"name","slug","category_id"},
     *             @OA\Property(property="name", type="string", example=".."),
     *              @OA\Property(property="slug", type="string", example="do-an"),
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
    public function update(CategoryRequest $request, $categoryId): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'slug' => 'required|string'
            ]);

            $category = $this->categoryService->updateCategory($categoryId, $request->all());

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('messages.success.category.update'),
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
                'message' => trans('messages.success.category.delete')
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
