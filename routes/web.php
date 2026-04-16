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
    if (auth()->user()->role === 'petugas' || auth()->user()->role === 'ketua_takmir') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('warga.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 2. RUTE KHUSUS PETUGAS/ADMIN ---
Route::middleware(['auth', 'verified', 'role:petugas'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    


    // Kelola Peminjaman (Booking)
    Route::get('/admin/loans', [\App\Http\Controllers\LoanController::class, 'adminIndex'])->name('admin.loans.index');
    Route::put('/admin/loans/{id}/approve', [\App\Http\Controllers\LoanController::class, 'approve'])->name('admin.loans.approve');
    Route::put('/admin/loans/{id}/reject', [\App\Http\Controllers\LoanController::class, 'reject'])->name('admin.loans.reject');
    Route::put('/admin/loans/{id}/return', [\App\Http\Controllers\LoanController::class, 'returnItem'])->name('admin.loans.return');
    Route::put('/admin/loans/{id}/handover', [\App\Http\Controllers\LoanController::class, 'handover'])->name('admin.loans.handover');
    Route::put('/admin/penalties/{id}/pay', [\App\Http\Controllers\LoanController::class, 'payPenalty'])->name('admin.penalties.pay');
    Route::get('/admin/reports/penalties', [\App\Http\Controllers\LoanController::class, 'penaltyReport'])->name('admin.reports.penalties');
    Route::get('/admin/items/export', [App\Http\Controllers\ItemController::class, 'export'])->name('items.export'); 
});

// Kelola Barang
    Route::resource('admin/items', ItemController::class);


// --- 3. RUTE KHUSUS WARGA ---
Route::middleware(['auth', 'verified', 'role:warga'])->group(function () {
    
    // 1. Halaman Beranda Warga
    Route::get('/warga/dashboard', function () {
        return view('warga.dashboard'); 
    })->name('warga.dashboard');

    // 2. Halaman Form Pengajuan
    Route::get('/warga/booking', [\App\Http\Controllers\LoanController::class, 'createWarga'])->name('warga.booking.create');
    Route::post('/warga/booking', [\App\Http\Controllers\LoanController::class, 'store'])->name('warga.booking.store');
    
    // 3. Halaman Riwayat Peminjaman
    Route::get('/warga/history', [\App\Http\Controllers\LoanController::class, 'historyWarga'])->name('warga.history');

    // Tambahkan baris ini di web.php (di luar prefix admin)
    Route::get('/peminjaman/tambah', [\App\Http\Controllers\LoanController::class, 'create'])->name('loans.create');
    Route::post('/peminjaman/simpan', [\App\Http\Controllers\LoanController::class, 'store'])->name('loans.store');
});


// --- 4. RUTE PROFIL BAWAAN ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';