<?php

namespace App\Http\Middleware;

use Closure;

class AccessAdmin
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
        if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin') ){
            return $next($request);
        }
        //return redirect('/');
        return route('login');
    }
}
