<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function login(LoginRequest $request): RedirectResponse
    {

        if (auth()->guard()->attempt($request->only('email', 'password'), $request->remember)) {
            $request->session()->regenerate();

            Inertia::flash('message', 'You have been logged in.');

            return to_route('dashboard');
        }

        return back()
            ->withErrors([
                'email' => 'Invalid credentials.',
            ]);
    }

    public function logout(): RedirectResponse
    {

        auth()->guard()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        Inertia::flash('message', 'You have been logged out.');

        Inertia::clearHistory();

        return to_route('login');
    }
}
