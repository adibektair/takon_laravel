<?php

namespace App\Http\Middleware;

use Closure;

class AnyRole
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
        if(auth()->user()){
            $role_id = $request->user()->role_id;
            if($role_id == 2 || $role_id == 1 || $role_id == 3 || $role_id == 4){
                return $next($request);
            }
        }
        return $next($request);
    }
}
