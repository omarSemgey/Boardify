<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('api')->check()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'You are already logged in.',
            ], 403);
        }

        return $next($request);
    }
}