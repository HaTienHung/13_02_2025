<?php


namespace App\Http\Controllers\Api\CMS;

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
 *     name="CMS Accounts",
 * )
 */
class AuthController extends Controller
{
    protected UserRepository $userRepository;
    private User $user;
    /**
     * Khởi tạo UserControllerV2.
     * @param User $user
     */

    public function __construct( User $user)
    {

        $this->user = $user;
    }
    /**
     * @OA\Post(
     *     path="/api/cms/admin/login",
     *     tags={"CMS Accounts"},
     *     summary="Đăng nhập để lấy token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="admin01@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678")
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
                ->OfRole(User::$admin)
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'errorCode' => 'E_UC2_1',
                    'message' => trans('message.errors.user.email_not_found'),
                    'data' => []
                ], Response::HTTP_BAD_REQUEST);
            }
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status'  => Response::HTTP_BAD_REQUEST,
                    'message' => trans('message.errors.auth.unauthenticated'),
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
                'message' => trans('message.success.user.login_success'),
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
     *     path="/api/cms/admin/logout",
     *     tags={"CMS Accounts"},
     *     summary="Đăng xuất",
     *     security={{"bearerAuth":{}}},
     *     operationId="admin/logout",
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
                    'message' => trans('message.errors.auth.unauthorized'),
                ], Constant::UNAUTHORIZED_CODE);
            }

            DB::commit();

            return response()->json([
                'status' => Constant::SUCCESS_CODE,
                'message' => trans('message.success.user.logout_success'),
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
}
