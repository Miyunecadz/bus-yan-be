<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class ValidToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->bearerToken()) {
            abort(401);
        }

        $decryptedToken = Crypt::decrypt($request->bearerToken());
        $data = explode('-', $decryptedToken);

        // index 1 - expected to be the application key
        if (config('app.key') !== $data[1]) {
            abort(401);
        }

        return $next($request);
    }
}
