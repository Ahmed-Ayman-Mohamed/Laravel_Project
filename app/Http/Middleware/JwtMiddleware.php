<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        auth()->shouldUse($guard);

        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            // Attempt to authenticate the token
            if (!JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'Invalid token'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Token is invalid or expired'], Response::HTTP_UNAUTHORIZED);
        }

        // If the token is valid, pass control to the next middleware/handler
        return $next($request);
    }
}
