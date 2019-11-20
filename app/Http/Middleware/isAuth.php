<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class isAuth
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
        
        $user = User::whereApiKey($request->bearerToken())->first();

        if(!$user){
            return response()->json(['error'=>'Sorry! you dont have access to this information']);
        }
        return $next($request);
    }
}
