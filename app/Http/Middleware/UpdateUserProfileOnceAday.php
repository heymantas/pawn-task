<?php

namespace App\Http\Middleware;

use App\Http\Resources\FailedResource;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserProfileOnceAday
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */

    public function handle(Request $request, Closure $next): FailedResource|JsonResponse
    {

        $user = auth('sanctum')->user();

        if ($user->last_profile_update && $user->last_profile_update->isToday()) {
            return new FailedResource(403, 'You can only update your profile once a day.');
        }

        return $next($request);
    }
}
