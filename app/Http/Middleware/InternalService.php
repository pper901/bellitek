<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternalService
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $token = $request->bearerToken();

        $expected =
            config(
                'services.generalclass.internal_api_key'
            );

        if (
            !$token ||
            !$expected ||
            !hash_equals($expected, $token)
        ) {

            return response()->json([
                'message' => 'Unauthorized service request.'
            ], 401);
        }

        return $next($request);
    }
}