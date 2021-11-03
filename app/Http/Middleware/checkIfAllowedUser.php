<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class checkIfAllowedUser
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
        $user = auth()->user();

        if(!$user->is_admin){
            $access_rights = auth()->user()->profile->role->getPermissionCodenamesAttribute();

            if(!in_array("UA", $access_rights)){
                abort(404);
            }
        }

        return $next($request);
    }
}
