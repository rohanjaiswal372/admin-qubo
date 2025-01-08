<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AuthenticateWithIONAuth
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
        if (!Auth::check()) {
          return redirect()->guest('auth/login');
        }else{
		  //echo("Logged in");	
		}
        return $next($request);
    }
}
