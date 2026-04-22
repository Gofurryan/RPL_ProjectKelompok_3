<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Prefix /dashboard untuk mengelompokkan API khusus dashboard
Route::prefix('dashboard')->group(function () {
    Route::get('/statistics', [DashboardApiController::class, 'statistics']);
    Route::get('/activities', [DashboardApiController::class, 'activities']);
}
);