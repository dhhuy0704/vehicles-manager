<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuickRefuelController;

Route::get('/', [QuickRefuelController::class, 'create'])->name('quick-refuel');
Route::post('/quick-refuel', [QuickRefuelController::class, 'store'])->name('quick-refuel.store');
