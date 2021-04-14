<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SupplierMiddleware
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
        if ($authUser!==null && $authUser->role=='supplier')
        {
            return $next($request);
        }else
        {
            return abort(401);
        }
    }
}
