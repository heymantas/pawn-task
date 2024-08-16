<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserProfileOnceAday
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = auth('sanctum')->user();

        if ($user->last_profile_update && $user->last_profile_update->isToday()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You can only update your profile once a day.',
            ], 403);
        }

        return $next($request);
    }
}
