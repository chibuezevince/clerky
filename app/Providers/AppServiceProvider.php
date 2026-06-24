<?php

namespace App\Providers;

use App\Exceptions\Inertia\TooManyRequestsException;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {
    }

    public function boot(): void {
        $this->configureDefaults();

        RateLimiter::for(
            'action',
            fn(Request $request) => Limit::perMinute(10)
                ->by($request->user()?->id ?: $request->ip())
                ->response(fn() => throw new TooManyRequestsException('Too many login attempts. Try again later.')),
        );
    }

    protected function configureDefaults(): void {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(app()->isProduction());

        Password::defaults(
            fn(): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
