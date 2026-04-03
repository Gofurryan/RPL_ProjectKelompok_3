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
    
    // Kelola Barang
    Route::resource('admin/items', ItemController::class);

    // Kelola Peminjaman (Booking)
    Route::get('/admin/loans', [\App\Http\Controllers\LoanController::class, 'adminIndex'])->name('admin.loans.index');
    Route::put('/admin/loans/{id}/approve', [\App\Http\Controllers\LoanController::class, 'approve'])->name('admin.loans.approve');
    Route::put('/admin/loans/{id}/reject', [\App\Http\Controllers\LoanController::class, 'reject'])->name('admin.loans.reject');
});


// --- 3. RUTE KHUSUS WARGA ---
Route::middleware(['auth', 'verified', 'role:warga'])->group(function () {
    
    // Halaman Dashboard & Form Booking
    Route::get('/warga/dashboard', function () {
        // Ambil semua barang yang tidak rusak/maintenance
        $items = \App\Models\Item::whereIn('status', ['Available', 'Borrowed'])->get();
        
        // Ambil riwayat peminjaman milik warga ini saja
        $myLoans = \App\Models\Loan::where('user_id', auth()->id())->latest()->get();
        
        return view('warga.dashboard', compact('items', 'myLoans')); 
    })->name('warga.dashboard');

    // Rute Submit Form Booking (Mengarah ke LoanController yang tadi kita buat)
    Route::post('/warga/booking', [\App\Http\Controllers\LoanController::class, 'store'])->name('warga.booking.store');
    
});


// --- 4. RUTE PROFIL BAWAAN ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';