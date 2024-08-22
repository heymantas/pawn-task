<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class AuthController extends Controller
{

    public function logout(): SuccessResource|FailedResource
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return new FailedResource(401, 'No authenticated user found.');
        }

        try {
            $user->currentAccessToken()->delete();
            return new SuccessResource('Successfully logged out');

        } catch (\Exception) {
            return new FailedResource(500, 'Failed to logout, please try again');
        }
    }

    public function login(AuthRequest $request, UserService $userService): UserResource|FailedResource
    {
        try {
            $credentials = $request->only('email', 'password');
            $user = $userService->attemptLogin($credentials, $request->device_name);

            if ($user) {
                return new UserResource($user);
            } else {
                return new FailedResource(401, 'Invalid login credentials.');
            }

        } catch (\Exception) {
            return new FailedResource(500, 'An error occurred during login. Please try again later.');
        }
    }


    public function register(AuthRequest $request, UserService $userService): UserResource|FailedResource
    {
        try {
            $user = $userService->createUser($request->all());
            return new UserResource($user);

        } catch (\Exception) {
            return new FailedResource(500, 'An error occurred during login. Please try again later.');
        }
    }
}
