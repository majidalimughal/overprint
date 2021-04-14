<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreMiddleware
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
        $authUser=auth()->user();
        if ($authUser!==null && $authUser->role=='store')
        {
            return $next($request);
        }else
        {
            return abort(401);
        }
    }
}
