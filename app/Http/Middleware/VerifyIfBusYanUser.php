<?php

namespace App\Http\Middleware;

use App\Helpers\TokenGenerator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyIfBusYanUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!TokenGenerator::validateTokenBasedOnRole(request()->bearerToken(), $role)) {
            abort(403);
        }

        return $next($request);
    }
}
