<?php

namespace App\Http\Controllers\Api\App;

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
 *     name="APP Accounts",
 * )
 */
class UserController extends Controller
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
     *     path="/api/app/users/{id}/info",
     *     summary="Xem thông tin hồ sơ người dùng đang đăng nhập",
     *     tags={"APP Accounts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID người dùng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Success.")
     *         )
     *     )
     * )
     */

    public function show(): JsonResponse
    {
        try {
            $user = $this->userRepository->findById(auth()->id());
            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => 'Thông tin chi tiết người dùng',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/app/users/update/{id}",
     *     summary="Cập nhật tài khoản",
     *     tags={"APP Accounts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID người dùng",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"_method", "name", "phone_number"},
     *                 @OA\Property(property="_method", type="string", example="PUT"),
     *                 @OA\Property(property="name", type="string", example="Bánh ngọt"),
     *                 @OA\Property(property="phone_number", type="string", example="0387768880"),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công"),
     *     @OA\Response(response=422, description="Lỗi validate")
     * )
     */

    public function update(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'phone_number' => 'required|string|unique:users,phone_number,' . auth()->id(),
            ]);

            $user = $this->userRepository->createOrUpdate($data, ['id' => auth()->id()]);

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
