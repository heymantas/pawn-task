<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{

    public function logout(): JsonResponse
    {
        $user = auth('sanctum')->user();

        try {
            $user->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out',
            ]);
        } catch (\Exception $e) {
            Log::error('Logout Failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout, please try again.',
            ], 500);
        }
    }

    public function login(AuthRequest $request, UserService $userService): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            $user = $userService->attemptLogin($credentials, $request->device_name);

            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'user' => new UserResource($user),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid login credentials.',
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error('Login Failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during login. Please try again later.',
            ], 500);
        }
    }


    public function register(AuthRequest $request, UserService $userService): JsonResponse
    {
        try {
            $user = $userService->createUser($request->all());
            return response()->json([
                'status' => 'success',
                'user' => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            Log::error('User Registration Failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'User registration failed. Please try again later.',
            ], 500);
        }
    }
}
