<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LastRefuelRecordController;

Route::get('/last-refuel-record', [LastRefuelRecordController::class, 'getLastRecord']);