<?php

namespace App\Http\Middleware;

use Closure;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard='user')
    {
        if(!\Auth::guard($guard)->check()){
            //return response('Not Allow Access',403);
            return redirect('login');
        }
        return $next($request);
    }
}
