<?php

namespace App\Http\Controllers\Api\App;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepository;
use App\Services\Product\ProductService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="APP Products",
 * )
 */

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
     *     tags={"APP Products"},
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
     *     path="/api",
     *     tags={"APP Products"},
     *     summary="Lấy ra danh sách sản phẩm mới nhất",

     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *             @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Success."),
     *          )
     *     ),
     * )
     */
    public function getLastestProducts(Request $request): JsonResponse
    {
        try{
            $listProduct = $this->productRepository->getLastestProducts();
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
     *     path="/api/products/id/{id}",
     *     tags={"APP Products"},
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
            $product = $this->productRepository->findBy('id',$id);
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
     * @OA\Get(
     *     path="/api/products/{slug}",
     *     tags={"APP Products"},
     *     summary="Lấy thông tin chi tiết sản phẩm",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Slug của sản phẩm",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A product detail",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function showBySlug($slug): JsonResponse
    {
        try {
            $product = $this->productRepository->findBy('slug',$slug);
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
}
