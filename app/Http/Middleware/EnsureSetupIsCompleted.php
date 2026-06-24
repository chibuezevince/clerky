<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSetupIsCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->hasCompletedSetup()) {
            return to_route('dashboard.setup');
        }

        return $next($request);
    }
}
