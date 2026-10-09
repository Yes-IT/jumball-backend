<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSuspended
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = auth()->user(); // works if user is authenticated

        if ($user && $user->is_suspended) {
            return response()->json([
                'message' => 'Your account has been suspended. Please contact support.'
            ], 403);
        }

        return $next($request);
    }
}
