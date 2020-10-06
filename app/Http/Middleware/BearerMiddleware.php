<?php
namespace App\Http\Middleware;
use Closure;
use Exception;

class BearerMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $key = "Whw0UiUHHFd4CGPFmDSiymxIm3gwbM1l";
        $token =  $request->header('Authorization');
        
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        
        if($token !== $key)
        {
            return response()->json([
                'error' => 'Invalid token.'
            ], 401);
        }
        return $next($request);
    }
}