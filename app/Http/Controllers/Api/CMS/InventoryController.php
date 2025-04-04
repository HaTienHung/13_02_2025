<?php

namespace App\Http\Controllers\Api\CMS;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Repositories\Inventory\InventoryRepository;
use App\Services\Inventory\InventoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $inventoryTransaction = $this->inventoryService->addStock($request->product_id, $request->quantity);

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('message.success.inventory.create'),
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
     *     path="/api/cms/inventories/show",
     *     tags={"CMS Inventories"},
     *     summary="Lấy báo cáo tồn kho",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Stock Report",
     *         @OA\JsonContent(ref="#/components/schemas/Inventory")
     *     )
     * )
     */
    public function getStockReport(): JsonResponse
    {
        $stockReport = $this->inventoryService->getStockReport();
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
                    'message' => trans('message.errors.not_found'),
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
}
