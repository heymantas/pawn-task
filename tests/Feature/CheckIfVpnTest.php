<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Http\Middleware\CheckIfVpn;
use App\Http\Resources\FailedResource;

class CheckIfVpnTest extends TestCase
{

    public function test_it_blocks_request_if_using_vpn()
    {

        $apiKey = config('services.proxy_check_io_key');
        $apiUrl = config('services.proxy_check_io_api_url');

        $vpnIpAddress = '185.214.96.77';

        Http::fake([
            "{$apiUrl}/{$vpnIpAddress}?key={$apiKey}&vpn=1" => Http::response([
                'status' => 'ok',
                $vpnIpAddress => ['proxy' => 'yes'],
            ]),
        ]);

        $response = $this->getJson('/api/test?ip=' . $vpnIpAddress);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Access denied due to VPN usage',
            ]);
    }
}
