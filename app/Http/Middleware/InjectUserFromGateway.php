<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectUserFromGateway
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('X-User-Uid')) {
            $request->merge([
                'user_uuid' => $request->header('X-User-Uid'),
            ]);
        }

        return $next($request);
    }
}
