<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') !== 'local') {
            $this->app['request']->server->set('HTTPS', true);
        }

        // Log authentication attempts
        \Illuminate\Support\Facades\Event::listen('Illuminate\Auth\Events\Attempting', function ($event) {
            \Illuminate\Support\Facades\Log::info('Login attempt', [
                'email' => $event->credentials['email'] ?? 'not provided',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        // Log authentication failures
        \Illuminate\Support\Facades\Event::listen('Illuminate\Auth\Events\Failed', function ($event) {
            \Illuminate\Support\Facades\Log::warning('Login failed', [
                'email' => $event->credentials['email'] ?? 'not provided',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });
    }
}
