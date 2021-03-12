<?php

namespace App\Http\Middleware;
use App\Traits\GeneralServices;

use Closure;

class TokenAuthMiddeware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	use GeneralServices;
	public function handle($request, Closure $next)
	{
		$token = $request->header('User-Token');
		
		if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'status' => false,
                'message' => 'User-Token not provided.',
            ], 401);
        }
		$checkAuthToken = $this->GET(env('AUTH_URL').'/api/user/check-auth');
		if($checkAuthToken['status'] == false){
			return response()->json($checkAuthToken, 406);
        }
		return $next($request);
	}
}
