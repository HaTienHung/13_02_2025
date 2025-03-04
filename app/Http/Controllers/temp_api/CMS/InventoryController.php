<?php

namespace App\Http\Controllers\Api\Cms;

use App\Services\Inventory\InventoryService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
  protected $inventoryService;

  /**
   * Khởi tạo InventoryController.
   * 
   * @param InventoryService $inventoryService
   */

  public function __construct(InventoryService $inventoryService)
  {
    $this->inventoryService = $inventoryService;
  }
  /**
   * @OA\Post(
   *     path="/api/cms/inventories/create",
   *     tags={"CMS Inventories"},
   *     summary="Import Product To Inventory",
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
  public function store(Request $request)
  {
    $request->validate([
      'product_id' => 'required|integer|exists:products,id',
      'quantity' => 'required|integer|min:1',
    ]);

    $inventoryTransaction = $this->inventoryService->addStock($request->product_id, $request->quantity);

    return response()->json(['message' => 'Thêm hàng vào kho thành công', 'inventoryTransaction' => $inventoryTransaction], Response::HTTP_CREATED);
  }
  /**
   * @OA\Get(
   *     path="/api/cms/inventories/show/{id}",
   *     tags={"CMS Inventories"},
   *     summary="Get stock by productID",
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
   *         description="A product detail",
   *         @OA\JsonContent(ref="#/components/schemas/Inventory")
   *     )
   * )
   */
  public function show($productId)
  {
    try {
      $currentStock = $this->inventoryService->getStock($productId);
      return response()->json([
        'message' => 'Số lượng hiện tại của sản phẩm trong kho',
        'currentStock' => $currentStock
      ], Response::HTTP_OK);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Không tìm thấy sản phẩm.'
      ], Response::HTTP_NOT_FOUND);
    }
  }
  /**
   * @OA\Get(
   *     path="/api/cms/inventories/show",
   *     tags={"CMS Inventories"},
   *     summary="Get stock report",
   *     security={{"bearerAuth":{}}},
   *     @OA\Response(
   *         response=200,
   *         description="Stock Report",
   *         @OA\JsonContent(ref="#/components/schemas/Inventory")
   *     )
   * )
   */
  public function getStockReport()
  {
    $stockReport = $this->inventoryService->getStockReport();
    return response()->json([
      'message' => 'Danh sách tồn kho của sản phẩm',
      'stockReport' => $stockReport
    ], Response::HTTP_OK);
  }
  /**
   * @OA\Get(
   *     path="/api/cms/inventories/show/{id}/transactions",
   *     tags={"CMS Inventories"},
   *     summary="Get Inventory Records of a Product",
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
  public function showInventoryRecords($productId)
  {
    try {
      $inventoryRecords = $this->inventoryService->showInventoryRecords($productId);

      return response()->json([
        'message' => 'Danh sách các giao dịch của sản phẩm',
        'stockReport' => $inventoryRecords
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Không tìm thấy sản phẩm hoặc không có giao dịch nào.'
      ], Response::HTTP_NOT_FOUND);
    }
  }
}
