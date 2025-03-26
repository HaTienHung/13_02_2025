<?php

namespace App\Http\Controllers\Api\Cms;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Imports\ProductV2Import;
use App\Repositories\Product\ProductRepository;
use App\Services\Product\ProductService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    protected ProductService $productService;
    protected ProductRepository $productRepository;

    /**
     * Khởi tạo ProductController.
     *
     * @param ProductService $productService
     * @param ProductRepository $productRepository
     */

    public function __construct(ProductService $productService, ProductRepository $productRepository)
    {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"APP"},
     *     summary="Lấy ra danh sách sản phẩm",
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
    public function index(Request $request): JsonResponse
    {
        try{
            $perpage = $request->perpage ?: Constant::PER_PAGE;
            $listProduct = $this->productRepository->listProduct($perpage);
            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Danh sách sản phẩm:',
                'data' => $listProduct
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status'=>Constant::FALSE_CODE,
                'message' => $th->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"APP"},
     *     summary="Lấy thông tin chi tiết sản phẩm",
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

    public function show($id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);
            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Thông tin sản phẩm',
                'product' => $product,
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => trans('message.errors.not_found'),
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/cms/product/create",
     *     tags={"CMS Products"},
     *     summary="Tạo sản phẩm mới",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|integer|min:0|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'messsage' => trans('message.success.product.create'),
                'data' => $this->productService->createProduct($request->all())
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'messsage' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/cms/product/update/{id}",
     *     summary="Cập nhật sản phẩm",
     *     tags={"CMS Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method", "name", "price", "category_id"},
     *                 @OA\Property(property="_method", type="string", example="PUT"),
     *                 @OA\Property(property="name", type="string", example="Bánh ngọt"),
     *                 @OA\Property(property="price", type="number", format="float", example=40000),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công"),
     *     @OA\Response(response=422, description="Lỗi validate")
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'category_id' => 'required|integer|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $product = $this->productService->updateProduct($id, $request->all());

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('message.success.product.update'),
                'product' => $product
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => trans('message.errors.not_found')
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
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
    public function destroy($id): JsonResponse
    {
        try {
            $this->productService->deleteProduct($id);
            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('message.success.product.delete')
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => trans('message.errors.not_found')
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/cms/product/import-excel",
     *     summary="Import danh sách sản phẩm từ file CSV",
     *     tags={"CMS Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="File CSV chứa danh sách sản phẩm"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Import thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Import thành công!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Lỗi validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="File không hợp lệ")
     *         )
     *     )
     * )
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        Excel::import(new ProductV2Import, $request->file('file'));

        return response()->json([
            'status' => Constant::SUCCESS_CODE,
            'message' => trans('message.success.success')
        ], Response::HTTP_OK);
    }
}
