<?php

namespace App\Http\Middleware;

use App\Http\Resources\FailedResource;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CheckIfVpn
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): JsonResponse|FailedResource|Response
    {
        //IP address from the user
        //$ipAddress = (new UserService())->getUserIPAddress();

        //IP address for testing (VPN)
        //$ipAddress = '185.214.96.77';

        //IP address for testing (non VPN)

        if(request()->has('ip')) {
            $ipAddress = request('ip');
        } else {
            $ipAddress = '8.8.8.8';
        }


        $apiKey = config('services.proxy_check_io_key');
        $apiUrl = config('services.proxy_check_io_api_url');

        $response = Http::get("{$apiUrl}/{$ipAddress}?key={$apiKey}&vpn=1");
        $data = $response->json();

        if (isset($data['status']) && $data['status'] === 'ok' && isset($data[$ipAddress]['proxy']) && $data[$ipAddress]['proxy'] === 'yes') {
            return new FailedResource(403, 'Access denied due to VPN usage');
        }

        return $next($request);
    }
}
