<?php

namespace App\Http\Controllers\Api\App;

use App\Enums\Constant;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="APP Accounts",
 * )
 */
class AuthController extends Controller
{
    protected UserRepository $userRepository;
    private User $user;
    /**
     * Khởi tạo UserControllerV2.
     * @param UserRepository $userRepository
     * @param User $user
     */

    public function __construct(UserRepository $userRepository, User $user)
    {
        $this->userRepository = $userRepository;
        $this->user = $user;
    }
    /**
     * @OA\Post(
     *     path="/api/app/users/login",
     *     tags={"APP Accounts"},
     *     summary="Đăng nhập để lấy token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="nguyenvana@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|abcde12345token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $user = $this->user->OfEmail($request->email)
                ->OfRole(User::$user)
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'errorCode' => 'E_UC2_1',
                    'message' => trans('messages.errors.user.email_not_found'),
                    'data' => []
                ], Response::HTTP_BAD_REQUEST);
            }
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status'  => Response::HTTP_BAD_REQUEST,
                    'message' => trans('messages.errors.auth.unauthenticated'),
                    'data' => []
                ], Response::HTTP_BAD_REQUEST);
            }

            $user->tokens()->delete();

            $data = [
                'token' => $user->createToken('API Token')->plainTextToken,
                'user' => $user
            ];

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => trans('messages.success.user.login_success'),
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $th->getMessage(),
                'data' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get (
     *     path="/api/app/users/logout",
     *     tags={"APP Accounts"},
     *     summary="Đăng xuất",
     *     security={{"bearerAuth":{}}},
     *     operationId="users/logout/user",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *             @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Success."),
     *          )
     *     ),
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Lấy user đang đăng nhập
            $user = Auth::user();

            if ($user) {
                // Xoá token hiện tại của user
                $user->currentAccessToken()->delete();
            } else {
                return response()->json([
                    'status' => Constant::UNAUTHORIZED_CODE,
                    'message' => trans('messages.errors.auth.unauthorized'),
                ], Constant::UNAUTHORIZED_CODE);
            }

            DB::commit();

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('messages.success.users.logout_sucess'),
                'data' => []
            ], Constant::SUCCESS_CODE);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => Constant::INTERNAL_SV_ERROR_CODE,
                'message' => $th->getMessage(),
                'data' => []
            ], Constant::INTERNAL_SV_ERROR_CODE);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/app/users/register",
     *     summary="Đăng ký tài khoản",
     *     tags={"APP Accounts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "email", "phone_number", "password", "password_confirmation"},
     *                 @OA\Property(property="name", type="string", example="Nguyen Van A"),
     *                 @OA\Property(property="email", type="string", format="email", example="nguyenvana@example.com"),
     *                 @OA\Property(property="address", type="string", format="email", example="Ha Noi"),
     *                 @OA\Property(property="phone_number", type="string", example="0387768880"),
     *                 @OA\Property(property="password", type="string", format="password", example="password123"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Đăng ký thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Đăng ký thành công"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="name", type="string", example="Nguyen Van A"),
     *                 @OA\Property(property="email", type="string", example="nguyenvana@example.com"),
     *                 @OA\Property(property="address", type="string", example="Ha Noi"),
     *                 @OA\Property(property="phone_number", type="string", example="0387768880")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Lỗi validate"),
     *     @OA\Response(response=400, description="Lỗi Bad Request")
     * )
     */

    public function createUser(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'address' => 'required|string',
                'phone_number' => 'required|string|max:15|unique:users,phone_number',
                'password' => 'required|string|min:8|confirmed',
            ]);
            // Hash mật khẩu trước khi lưu
            $data['password'] = bcrypt($data['password']);

            $user = $this->userRepository->createOrUpdate($data);

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('messages.success.user.create'),
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Constant::FALSE_CODE,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
