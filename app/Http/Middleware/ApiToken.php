<?php

namespace App\Http\Middleware;

use App\MobileUser;
use Closure;

class ApiToken
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
        if(!$request->token){
            $token = $request->bearerToken();
            $request->token = $token;

        }
        return $next($request);
    }
}
