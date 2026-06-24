<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsContributor {
    public function handle(Request $request, Closure $next): Response {
        $user = $request->user();
        if (!$user->can_contribute) {
            Inertia::flash('error', 'You cannot make contributions at this time.');
            return back()->with('contributor', false);
        }

        return $next($request);
    }
}
