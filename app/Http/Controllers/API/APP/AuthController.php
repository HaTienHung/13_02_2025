<?php

namespace App\Http\Controllers\Api\App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
  /**
   * @OA\Post(
   *     path="/api/login",
   *     tags={"Authentication"},
   *     summary="Login to get a Bearer Token",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"email", "password"},
   *             @OA\Property(property="email", type="string", example="user@example.com"),
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
  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
      return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('API Token')->plainTextToken;

    return response()->json(['token' => $token]);
  }
}
