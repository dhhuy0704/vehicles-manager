<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuickRefuelController;

// Debug route
Route::get('/debug-login', function() {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'cookies' => request()->cookies->all(),
        'headers' => collect(request()->headers->all())
            ->filter(fn($value, $key) => in_array($key, [
                'host',
                'user-agent',
                'accept',
                'x-forwarded-for',
                'x-forwarded-proto',
                'x-forwarded-host',
                'cf-connecting-ip',
                'cf-ray',
                'cf-visitor'
            ]))
            ->all()
    ]);
});

Route::get('/', [QuickRefuelController::class, 'create'])->name('quick-refuel');
Route::post('/quick-refuel', [QuickRefuelController::class, 'store'])->name('quick-refuel.store');
