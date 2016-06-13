<?php

namespace App\Http\Middleware;

use Closure, Response;

class CheckAjaxRequest
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
        if (!$request->ajax()) {
            return Response::json([
                'message' => 'Only ajax requests are allowed'
            ], 405);
        }

        return $next($request);
    }
}
