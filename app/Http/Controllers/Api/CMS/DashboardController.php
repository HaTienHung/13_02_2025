<?php

namespace App\Http\Controllers\Api\CMS;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\DashboardRepository;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="CMS Dashboard",
 * )
 */
class DashboardController extends Controller
{
    protected DashboardRepository $dashboardRepository;

    /**
     * Khởi tạo UserControllerV2.
     * @param DashboardRepository $dashboardRepository
     */

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/cms/dashboard",
     *     tags={"CMS Dashboard"},
     *     summary="Lấy thống kê tổng số khách hàng, đơn hàng, doanh thu",
     *     description="Trả về tổng số khách hàng (role=user), đơn hàng và tổng doanh thu",
     *     operationId="getStats",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thống kê thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_customers", type="integer", example=120),
     *             @OA\Property(property="total_orders", type="integer", example=432),
     *             @OA\Property(property="total_revenue", type="number", format="float", example=1045000.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không có quyền truy cập"
     *     )
     * )
     */

    public function stats(): JsonResponse
    {
        $stats = $this->dashboardRepository->getStats();

        return response()->json([
            'status' => Constant::SUCCESS_CODE,
            'data' => $stats
        ]
        );
    }
    /**
     * @OA\Get(
     *     path="/api/cms/dashboard/latest-invoices",
     *     tags={"CMS Dashboard"},
     *     summary="Lấy ra danh sách 5 đơn hàng mới nhất",
     *     description="Trả về 5 đơn hàng mới nhất",
     *     operationId="getLatestInvoices",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thống kê thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="latest-invoices", type="integer", example=120),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không có quyền truy cập"
     *     )
     * )
     */
    public function latestInvoices(): JsonResponse
    {
        $latestInvoices = $this->dashboardRepository->latestInvoice();

        return response()->json([
            'status' => Constant::SUCCESS_CODE,
            'data' => $latestInvoices
        ]);
    }
    /**
     * @OA\Get(
     *     path="/api/cms/dashboard/revenue",
     *     tags={"CMS Dashboard"},
     *     summary="Lấy ra doanh thu theo ngày",
     *     description="Trả về doanh thu ngày",
     *     operationId="getRevenueByDay",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thống kê thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="latest-invoices", type="integer", example=120),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không có quyền truy cập"
     *     )
     * )
     */
    public function getRevenueByDay(): JsonResponse
    {
        $revenue = $this->dashboardRepository->getRevenueByDay();

        return response()->json([
            'status' => Constant::SUCCESS_CODE,
            'data' => $revenue
        ]);
    }
}
