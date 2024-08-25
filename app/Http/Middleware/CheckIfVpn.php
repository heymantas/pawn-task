<?php

namespace App\Http\Middleware;

use App\Http\Resources\FailedResource;
use App\Services\UserService;
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
    public function handle(Request $request, Closure $next): JsonResponse|FailedResource
    {
        if (config('app.env') === 'production') { //get actual IP address in production
            $ipAddress = (new UserService())->getUserIPAddress();
        } elseif (request()->has('ip')) { //used in PHPUnit testing
            $ipAddress = request('ip');
        } else { //Dev environment
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
