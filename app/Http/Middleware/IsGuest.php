<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsGuest
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
        if(!Auth::check()) {
            return $next($request);            
        } else {
            return redirect(route('user-dashboard'))->with("message","Unauthorized action!. You are not authorized to access the page you requested")
                                                    ->with("alert-class","alert-danger");
        }
    }
}
