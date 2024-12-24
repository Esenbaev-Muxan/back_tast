<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="My API",
 *     description="This is the API documentation for the application",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="support@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */


class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login", "password"},
     *             @OA\Property(property="login", type="string", example="user"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="token_string_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Incorrect password or login",
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('login', $request->login)->first();
        if ($request->password) {
            if (!password_verify($request->password, $user->password)) {
                return $this->error(__('errors.incorrect_password'), 401);
            }
        }

        return $this->success([
            "token" => $user->createToken("token")->plainTextToken
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create(array_merge(
            $request->validated(),
            ['password' => bcrypt($request->password)],
        ));

        return $this->success([
            $user,
            "token" => $user->createToken("token")->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        // Ensure the user is authenticated
        $user = $request->user();
    
        if ($user) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });
    
            return response()->json(['message' => 'Logged out successfully'], 200);
        }
    
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
