<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->token = $user->createToken(
            str_replace(" ", "_", $data['device_name']) . '_' . time()
        )->plainTextToken;

        return $user;
    }

    public function attemptLogin(array $credentials, $deviceName): ?Authenticatable
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $user->token = $user->createToken(
                str_replace(" ", "_", $deviceName) . '_' . time()
            )->plainTextToken;

            return $user;
        }

        return null;
    }

}
