<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class Oauth2Controller extends Controller {
    private const ALLOWED_PROVIDERS = ['google', 'twitter'];

    public function redirect(string $provider) {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function link(string $provider) {
        $this->validateProvider($provider);

        session()->put('oauth_link', true);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider) {
        $this->validateProvider($provider);

        try {
            $socialiteUser = Socialite::driver($provider)->user();

            $email = $socialiteUser->getEmail();

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return to_route('login')->withErrors([
                    'email' => 'Could not retrieve a valid email from the provider.',
                ]);
            }

            $avatarUrl = $this->getValidatedAvatar($socialiteUser->getAvatar());

            if (session()->pull('oauth_link')) {
                $user = auth()->user();
            } else {
                $username = explode('@', $email)[0];

                $user = User::firstOrCreate([
                    'email' => $email,
                ], [
                    'name' => $socialiteUser->getName() ?? 'User',
                    'username' => $socialiteUser->getNickname() ?? $username ?? 'user' . random_int(1000, 9999),
                    'email_verified_at' => now(),
                    'password' => null,
                ]);

                if ($user->wasRecentlyCreated) {
                    $user->details()->updateOrCreate(
                        ['user_id' => $user->id],
                        ['avatar' => $avatarUrl]
                    );
                }

                $user->update([
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);

                auth()->guard()->login($user);
            }

            $user->socialAccounts()->updateOrCreate(
                ['provider' => $provider, 'provider_id' => $socialiteUser->getId()],
                [
                    'token' => $socialiteUser->token,
                    'refresh_token' => $socialiteUser->refreshToken,
                    'avatar' => $avatarUrl,
                ]
            );

            return Inertia::render('Auth/OAuthCallback', [
                'status' => 'success',
            ]);
        } catch (\Throwable $th) {
            report($th);
            Inertia::flash('message', 'Authentication failed. Please, try again');

            return Inertia::render('Auth/OAuthCallback', [
                'status' => 'error',
            ]);
        }
    }

    public function unlink(string $provider) {
        $this->validateProvider($provider);

        $user = auth()->user();
        $socialAccount = $user->socialAccounts()->where('provider', $provider)->firstOrFail();

        $hasPassword = !is_null($user->password);
        $otherAccounts = $user->socialAccounts()->where('provider', '!=', $provider)->count();

        if (!$hasPassword && $otherAccounts === 0) {
            return back()->withErrors([
                'provider' => 'Cannot disconnect your only login method. Set a password first.',
            ]);
        }

        $socialAccount->delete();

        return back();
    }

    private function validateProvider(string $provider): void {
        if (!in_array($provider, self::ALLOWED_PROVIDERS, strict: true)) {
            abort(400, 'Unsupported provider.');
        }
    }

    private function getValidatedAvatar(?string $url): ?string {
        if (empty($url) || !str_starts_with($url, 'https://')) {
            return null;
        }

        $trustedDomains = [
            'googleusercontent.com',
            'avatars.githubusercontent.com',
            'platform-lookaside.fbsbx.com',
            'graph.facebook.com',
        ];

        $host = parse_url($url, PHP_URL_HOST);

        if (empty($host)) {
            return null;
        }

        foreach ($trustedDomains as $domain) {
            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                return $url;
            }
        }

        return null;
    }
}
