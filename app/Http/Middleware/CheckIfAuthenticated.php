<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if(!auth()->user()->is_admin && in_array("SVA", auth()->user()->profile->role->getPermissionCodenamesAttribute())){
                return redirect('/backend/servers/' . auth()->user()->profile->branch->server->id);
            }else{
                return redirect('/backend/dashboards');
            }
            
        }
        return $next($request);
    }
}
