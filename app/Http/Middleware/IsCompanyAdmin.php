<?php

namespace App\Http\Middleware;

use Closure;

class IsCompanyAdmin
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
            if($request->user()->role_id == 3){
                return $next($request);
            }
        }

        return redirect()->route('forbidden');

    }

}
