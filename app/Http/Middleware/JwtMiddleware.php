<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\ApiResponseTrait; // Import the trait

class JwtMiddleware
{
    use ApiResponseTrait;

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
            return $this->unauthorizedResponse('Token not provided');
        }

        try {
            // Attempt to authenticate the token
            if (!JWTAuth::parseToken()->authenticate()) {
                return $this->unauthorizedResponse('Invalid token');
            }
        } catch (JWTException $e) {
            return $this->unauthorizedResponse('Token is invalid or expired');
        }

        // If the token is valid, pass control to the next middleware/handler
        return $next($request);
    }
}
