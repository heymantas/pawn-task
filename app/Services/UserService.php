<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserService
{
    public function createUser(array $data): User
    {
        //IP address from the user
        //$ipAddress = (new UserService())->getUserIPAddress();

        //IP address for testing (VPN)
        //$ipAddress = '185.214.96.77';

        //IP address for testing (non VPN)

        $ipAddress = '8.8.8.8';
        $countryData = $this->getUserCountryByIP($ipAddress);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->isocode = $countryData['isocode'];
        $user->country = $countryData['country'];
        $user->ip_address = $ipAddress;
        $user->save();

        (new UserWalletService())->createUserWallet($user->id);

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

    public function getUserCountryByIP($ipAddress): array
    {
        $apiKey = config('services.proxy_check_io_key');
        $apiUrl = config('services.proxy_check_io_api_url');

        $response = Http::get("{$apiUrl}/{$ipAddress}?key={$apiKey}&asn=1");
        $data = $response->json();

        return [
          'isocode' => $data[$ipAddress]['isocode'],
          'country' => $data[$ipAddress]['country'],
        ];

    }

    public function getUserIPAddress()
    {
        foreach (
            array(
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR'
            ) as $key
        ) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var(
                            $ip,
                            FILTER_VALIDATE_IP,
                            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                        ) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return the server IP if the client IP is not found using this method.

    }
}
