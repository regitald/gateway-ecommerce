<?php

namespace App\Http\Middleware;
use App\Traits\GeneralServices;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;


use Closure;

class TokenAPIMiddeware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	use GeneralServices;
	public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Api-Token');
        
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'status' => false,
                'message' => 'Api-Token not provided.',
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Provided Api-Token is expired.',
            ], 402);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Auth - '.$e->getMessage().' - '.$e->getFile().' - L '.$e->getLine(),
            ],(method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
        return $next($request);
    }
}
