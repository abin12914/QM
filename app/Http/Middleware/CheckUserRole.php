<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $userRole = Auth::user()->role;

        if($role !== $userRole) {
            return redirect()->back()->with("message"," Unauthorized action. You don't have permission to the option you requested.")
                                                ->with("alert-class","alert-danger");
        }
        return $next($request);
    }
}
