<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response([
                    'meta' => [
                        'message' => 'Token invalid',
                        'code' => 500,
                        'status' => 'error'
                    ],
                    'data' => null
                ], Response::HTTP_INTERNAL_SERVER_ERROR);

            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                // return response()->json(['status' => 'Token is Expired']);
                return response([
                    'meta' => [
                        'message' => 'Token is expired',
                        'code' => 500,
                        'status' => 'error'
                    ],
                    'data' => null
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            } else {
                // return response()->json(['status' => 'Authorization Token not found']);
                // return format_response('error', Response::HTTP_INTERNAL_SERVER_ERROR, 'Authorization token not found!', null);
                return response([
                    'meta' => [
                        'message' => 'Authorization token not found',
                        'code' => 500,
                        'status' => 'error'
                    ],
                    'data' => null
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return $next($request);
        // return response()->json(['status' => 'User not authenticated']);
        // return format_response('error', Response::HTTP_INTERNAL_SERVER_ERROR, 'User not authenticated', null);
    }
}