<?php

namespace App\Http\Controllers\Api\CMS;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="CMS Users",
 * )
 */
class UserControllerV2 extends Controller
{
    protected UserRepository $userRepository;

    /**
     * Khởi tạo UserControllerV2.
     * @param UserRepository $userRepository
     */

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/cms/users",
     *     tags={"CMS Users"},
     *     security={{"bearerAuth":{}}},
     *     summary="Lấy ra danh sách người dùng",
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
        try {
            $perpage = $request->perpage ?: Constant::PER_PAGE;
            $listUser = $this->userRepository->listUser($perpage);
            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Danh sách người dùng:',
                'data' => $listUser
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cms/users/{id}",
     *     tags={"CMS Users"},
     *     security={{"bearerAuth":{}}},
     *     summary="Lấy thông tin chi tiết người dùng",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của người dùng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A product detail",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $user = $this->userRepository->findById($id);
            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Chi tiết thông tin người dùng:',
                'data' => $user
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
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/cms/users/update/{id}",
     *     summary="Cập nhật người dùng",
     *     tags={"CMS Users"},
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
     *                 required={"_method", "name", "phone_number"},
     *                 @OA\Property(property="_method", type="string", example="PUT"),
     *                 @OA\Property(property="name", type="string", example="Bánh ngọt"),
     *                 @OA\Property(property="phone_number", type="0387768880"),
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
            $data = $request->validate([
                'name' => 'required|string',
                'phone_number' => 'required|string|unique:users,phone_number,' . $id,
            ]);

            $user = $this->userRepository->createOrUpdate($data, ['id'=>$id]);

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('message.success.user.update'),
                'data' => $user
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
}
