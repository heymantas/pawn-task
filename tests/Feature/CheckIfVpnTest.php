<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Http\Middleware\CheckIfVpn;
use App\Http\Resources\FailedResource;

class CheckIfVpnTest extends TestCase
{

    public function test_it_blocks_request_if_using_vpn()
    {
        $vpnIpAddress = '185.214.96.77'; //Latvian VPN

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/get-questions?ip=' . $vpnIpAddress);

        $user->delete();

        $response->assertStatus(403)->assertJson(['message' => 'Access denied due to VPN usage']);
    }
}
