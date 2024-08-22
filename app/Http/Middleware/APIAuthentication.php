<?php

namespace App\Http\Middleware;

use App\Http\Resources\FailedResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class APIAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('sanctum')->check()) {
            return $next($request);
        }

        return new FailedResource(401, 'Unauthorized.');
    }
}
