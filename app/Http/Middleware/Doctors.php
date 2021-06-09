<?php

namespace App\Http\Middleware;
use Closure;

class Doctors
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
        if(Auth::user()->hasRole('Doctor') || Auth::user()->hasRole('Admin') ){
            return $next($request);
        }
        return redirect('/');
    }
}
