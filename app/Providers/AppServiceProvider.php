<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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

        Schema::defaultStringLength(191);

        // Log all authentication events
        \Illuminate\Support\Facades\Event::listen('Illuminate\Auth\Events\Attempting', function ($event) {
            \Illuminate\Support\Facades\Log::info('Login attempting', [
                'email' => $event->credentials['email'] ?? 'not provided',
                'password_length' => strlen($event->credentials['password'] ?? ''),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        });

        \Illuminate\Support\Facades\Event::listen('Illuminate\Auth\Events\Authenticated', function ($event) {
            \Illuminate\Support\Facades\Log::info('User authenticated', [
                'user_id' => $event->user->id ?? 'none',
                'email' => $event->user->email ?? 'none',
                'ip' => request()->ip(),
                'session_id' => session()->getId()
            ]);
        });

        \Illuminate\Support\Facades\Event::listen('Illuminate\Auth\Events\Failed', function ($event) {
            \Illuminate\Support\Facades\Log::warning('Login failed', [
                'email' => $event->credentials['email'] ?? 'not provided',
                'ip' => request()->ip(),
                'error' => $event->exception ? $event->exception->getMessage() : 'No exception'
            ]);
        });

        \Illuminate\Support\Facades\Event::listen('Illuminate\Auth\Events\Login', function ($event) {
            \Illuminate\Support\Facades\Log::info('Login successful', [
                'user_id' => $event->user->id ?? 'none',
                'email' => $event->user->email ?? 'none',
                'ip' => request()->ip(),
                'session_id' => session()->getId()
            ]);
        });
    }
}
