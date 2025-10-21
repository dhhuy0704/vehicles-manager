<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuickRefuelController;

// Debug routes
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

// Test login endpoint
Route::post('/debug-login-test', function(\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('Login test received', [
        'email' => $request->email,
        'has_password' => !empty($request->password),
        'token_match' => hash_equals(
            hash_hmac('sha256', $request->input('_token'), base64_decode(substr(config('app.key'), 7))),
            hash_hmac('sha256', session()->token(), base64_decode(substr(config('app.key'), 7)))
        ),
        'session_id' => session()->getId(),
        'all_input' => $request->all()
    ]);
    
    return response()->json([
        'received_data' => $request->all(),
        'session_id' => session()->getId(),
        'token_valid' => $request->hasValidSignature(),
        'cookies' => $request->cookies->all()
    ]);
});

// Debug Livewire route
Route::get('/debug-livewire', function() {
    return response()->json([
        'csrf' => csrf_token(),
        'livewire_id' => session()->get('livewire'),
        'session_id' => session()->getId(),
        'cookies' => request()->cookies->all(),
        'headers' => collect(request()->headers->all())
            ->filter(fn($value, $key) => in_array($key, [
                'x-livewire',
                'x-csrf-token',
                'x-requested-with',
                'cf-connecting-ip',
                'cf-ray',
                'cf-visitor'
            ]))
            ->all()
    ]);
});

// Test raw authentication
Route::post('/test-auth', function(\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return response()->json([
            'success' => true,
            'user' => \Illuminate\Support\Facades\Auth::user(),
            'session_id' => session()->getId()
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid credentials'
    ], 401);
});

Route::get('/', [QuickRefuelController::class, 'create'])->name('quick-refuel');
Route::post('/quick-refuel', [QuickRefuelController::class, 'store'])->name('quick-refuel.store');
