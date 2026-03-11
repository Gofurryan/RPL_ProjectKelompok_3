<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// --- 1. TRAFFIC CONTROLLER (Pencegat agar tidak 404) ---
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'Admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('warga.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 2. RUTE KHUSUS ADMIN ---
Route::middleware(['auth', 'verified', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    // Tambahkan untuk rute CRUD Barang:
    Route::resource('admin/items', ItemController::class);
});


// --- 3. RUTE KHUSUS WARGA ---
Route::middleware(['auth', 'verified', 'role:Warga'])->group(function () {
    Route::get('/warga/dashboard', function () {
        return view('warga.dashboard');
    })->name('warga.dashboard');
});


// --- 4. RUTE PROFIL BAWAAN ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';