<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::user() || !Auth::user()->is_admin){
            return response()->json([
                'message' => 'Unauthorized'
            ] , 403);
        }

        return $next($request);
    }
}
