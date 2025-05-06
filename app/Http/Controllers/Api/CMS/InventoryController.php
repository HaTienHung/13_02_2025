<?php

namespace App\Http\Controllers\Api\CMS;

use App\Enums\Constant;
use App\Exports\StockReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CMS\InventoryRequest;
use App\Repositories\Inventory\InventoryRepository;
use App\Services\Inventory\InventoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @OA\Tag(
 *     name="CMS Inventories",
 * )
 */

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;
    protected InventoryRepository $inventoryRepository;

    /**
     * Khởi tạo InventoryController.
     *
     * @param InventoryService $inventoryService
     * @param InventoryRepository $inventoryRepository
     */

    public function __construct(InventoryService $inventoryService, InventoryRepository $inventoryRepository)
    {
        $this->inventoryService = $inventoryService;
        $this->inventoryRepository = $inventoryRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/cms/inventories/create",
     *     tags={"CMS Inventories"},
     *     summary="Thêm số lượng sản phẩm vào kho",
     *     security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *         in="header",
     *         name="X-Localization",  
     *         required=false,
     *         description="Ngôn ngữ",
     *         @OA\Schema(
     *             type="string",
     *             example="vi",  
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id","quantity"},
     *             @OA\Property(property="product_id", type="integer", example="1"),
     *             @OA\Property(property="quantity", type="number", example=40),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Imported Product",
     *         @OA\JsonContent(ref="#/components/schemas/Inventory")
     *     )
     * )
     */
    public function store(InventoryRequest $request): JsonResponse
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $inventoryTransaction = $this->inventoryService->addStock($request->product_id, $request->quantity);

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('messages.success.inventory.create'),
                'data' => $inventoryTransaction
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
                'data' => []
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cms/inventories",
     *     tags={"CMS Inventories"},
     *     security={{"bearerAuth":{}}},
     *     summary="Lấy báo cáo tồn kho",
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
    public function getStockReport(): JsonResponse
    {
        $stockReport = $this->inventoryService->getStockReportWithPaginate();
        return response()->json([
            'status' => Constant::SUCCESS_CODE,
            'message' => 'Danh sách tồn kho của sản phẩm',
            'data' => $stockReport
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/cms/inventories/show/{id}/transactions",
     *     tags={"CMS Inventories"},
     *     summary="Lấy ra tất cả giao dịch của 1 sản phẩm",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của sản phẩm",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of inventory transactions for the product",
     *         @OA\JsonContent(ref="#/components/schemas/Inventory")
     *     )
     * )
     */
    public function showInventoryRecords($productId): JsonResponse
    {
        try {
            $inventoryRecords = $this->inventoryRepository->findAllBy(['product_id' => $productId]);

            if ($inventoryRecords->isEmpty()) {
                return response()->json([
                    'status' => Constant::FALSE_CODE,
                    'message' => trans('messages.errors.not_found'),
                    'data' => $inventoryRecords
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Danh sách các giao dịch của sản phẩm',
                'data' => $inventoryRecords
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * @OA\Get (
     *     path="/api/cms/inventories/stock-report/export-excel",
     *     tags={"CMS Inventories"},
     *     summary="Xuất Excel",
     *     security={{"bearerAuth":{}}},
     *     operationId="stock-report/export-excel",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *             @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Success."),
     *          )
     *     ),
     * )
     */
    public function exportExcelStockReport()
    {
        try {
            $stockReport = $this->inventoryService->getStockReport();
        } catch (\Throwable $th) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $th->getMessage(),
                'data' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return Excel::download(
            new StockReportExport($stockReport),
            'StockReport' . str_replace('/', '-', date('Y/m/d')) . '.xlsx'
        );
    }
    /**
     * @OA\Get (
     *     path="/api/cms/inventories/transactions/export-excel",
     *     tags={"CMS Inventories"},
     *     summary="Xuất Excel",
     *     security={{"bearerAuth":{}}},
     *     operationId="transactions/export-excel",
     *        @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của sản phẩm",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *             @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Success."),
     *          )
     *     ),
     * )
     */
    // public function exportExcelTransactions($productId)
    // {
    //     try {
    //         $inventoryRecords = $this->inventoryRepository->findAllBy(['product_id' => $productId]);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => Constant::FALSE_CODE,
    //             'message' => $th->getMessage(),
    //             'data' => []
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    //     return Excel::download(
    //         new TransactionsExport($inventoryRecords),
    //         'TransactionsReport' . str_replace('/', '-', date('Y/m/d')) . '.xlsx'
    //     );
    // }
}
