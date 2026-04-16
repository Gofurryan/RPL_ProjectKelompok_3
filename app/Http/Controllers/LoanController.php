<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    // Menampilkan halaman form booking untuk warga
    public function createWarga()
    {
        $items = \App\Models\Item::whereIn('status', ['Available', 'Borrowed'])->get();
        return view('warga.booking', compact('items'));
    }

    // Menampilkan halaman riwayat untuk warga
    public function historyWarga()
    {
        // Ini akan mengambil data Loan, sekaligus mengambil detailnya, sekaligus mengambil nama itemnya
        $loans = \App\Models\Loan::where('user_id', auth()->id())
                    ->with(['details.item', 'penalty']) // Pastikan relasi penalty juga dipanggil jika ada denda
                    ->latest()
                    ->get();
        return view('warga.history', compact('loans'));
    }

    public function create()
{
    // Ambil data barang yang stoknya lebih dari 0 untuk ditampilkan di form booking
    $items = \App\Models\Item::where('stock', '>', 0)->get();
    
    // Pastikan file view ini sudah kamu buat di resources/views/loans/create.blade.php
    return view('loans.create', compact('items'));
}

    public function store(Request $request)
    {
        // 1. VALIDASI INPUT (Format Array / Keranjang Belanja)
        $request->validate([
            'items' => 'required|array|min:1', // Wajib ada minimal 1 barang
            'items.*.id' => 'required|exists:items,id', // ID barang harus valid
            'items.*.qty' => 'required|integer|min:1',  // Jumlah minimal 1
            'loan_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after:loan_date',
        ], [
            'items.required' => 'Silakan pilih minimal satu barang.',
            'loan_date.required' => 'Rencana tanggal diambil wajib diisi.',
            'loan_date.after_or_equal' => 'Tanggal pengambilan tidak boleh di masa lalu.',
            'due_date.required' => 'Rencana tanggal dikembalikan wajib diisi.',
            'due_date.after' => 'Tanggal pengembalian harus setelah tanggal peminjaman.',
        ]);

        $user = auth()->user();

        // 2. RULE KEAMANAN WARGA (Sama seperti sebelumnya)
        // Rule 1: Satu Transaksi Aktif
        $activeLoan = \App\Models\Loan::where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'Approved', 'Active'])
            ->exists();

        if ($activeLoan) {
            return back()->withErrors(['conflict' => 'Sistem Menolak: Anda masih memiliki transaksi peminjaman yang sedang berjalan.'])->withInput();
        }

        // Rule 2: Cek Blacklist (Terlambat / Denda)
        $hasOverdue = \App\Models\Loan::where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('due_date', '<', \Carbon\Carbon::now())
            ->exists();

        $hasUnpaidPenalty = \App\Models\Penalty::whereHas('loan', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('payment_status', 'Unpaid')->exists();

        if ($hasOverdue || $hasUnpaidPenalty) {
            return back()->withErrors(['conflict' => 'Sistem Menolak (Blacklist): Anda memiliki keterlambatan atau denda belum lunas.'])->withInput();
        }

        // 3. RULE KUOTA TOTAL (Maksimal 10 Barang)
        $totalQty = 0;
        foreach ($request->items as $itemData) {
            $totalQty += $itemData['qty'];
        }

        if ($totalQty > 10) {
            return back()->withErrors(['conflict' => 'Batas Kuota: Maksimal total barang yang dipinjam adalah 10 unit per transaksi.'])->withInput();
        }

        // 4. EKSEKUSI PENYIMPANAN & CEK STOK (Dengan Database Transaction)
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                $startDate = $request->loan_date;
                $endDate = $request->due_date;

                // A. Buat Header Peminjaman (di tabel loans)
                $loan = \App\Models\Loan::create([
                    'user_id' => auth()->id(),
                    'loan_date' => $startDate,
                    'due_date' => $endDate,
                    'status' => 'Pending',
                ]);

                $soundSystemCount = 0; // Variabel penghitung kategori Sound System

                // B. Looping setiap barang yang ada di keranjang
                foreach ($request->items as $itemData) {
                    $itemId = $itemData['id'];
                    $qtyRequested = $itemData['qty'];
                    $item = \App\Models\Item::findOrFail($itemId);

                    // --- CONFLICT DETECTION LOGIC TERBARU (Mengambil dari tabel Detail) ---
                    $bookedCount = \App\Models\LoanDetail::where('item_id', $itemId)
                        ->whereHas('loan', function ($query) use ($startDate, $endDate) {
                            $query->whereIn('status', ['Approved', 'Active'])
                                  ->where(function ($q) use ($startDate, $endDate) {
                                      $q->whereBetween('loan_date', [$startDate, $endDate])
                                        ->orWhereBetween('due_date', [$startDate, $endDate])
                                        ->orWhere(function ($q2) use ($startDate, $endDate) {
                                            $q2->where('loan_date', '<=', $startDate)
                                               ->where('due_date', '>=', $endDate);
                                        });
                                  });
                        })->sum('quantity'); // Menggunakan SUM karena 1 transaksi bisa pinjam > 1 unit

                    // Jika stok terpakai + yang mau dipinjam melebihi stok utama, Gagalkan!
                    if (($bookedCount + $qtyRequested) > $item->stock) {
                        $sisa = $item->stock - $bookedCount;
                        throw new \Exception("Stok tidak mencukupi untuk '{$item->name}'. (Sisa kuota tanggal tersebut: {$sisa} unit).");
                    }

                    // C. Simpan ke tabel detail
                    \App\Models\LoanDetail::create([
                        'loan_id' => $loan->id,
                        'item_id' => $itemId,
                        'quantity' => $qtyRequested,
                    ]);
                }
            });

            // Jika semua lolos, tampilkan pesan sukses dengan reminder baru
            return redirect()->route('warga.history')->with('success', [
    'Peminjaman berhasil diajukan! Mohon pantau menu Riwayat.',
    'Ingat: Barang yang telah disetujui harus diambil dalam maksimal 2 hari, atau sistem akan membatalkannya secara otomatis.'
]);

        } catch (\Exception $e) {
            // Jika ada rule yang dilanggar di dalam transaction (throw Exception), tangkap errornya dan lemparkan ke layar
            return back()->withErrors(['conflict' => $e->getMessage()])->withInput();
        }
    }
    // ==========================================
    // AREA ADMIN / PETUGAS
    // ==========================================
    
    // 1. Menampilkan daftar semua pengajuan
    public function adminIndex()
    {
        // Mengambil data peminjaman dengan relasi User (Warga) dan Details (Isi Keranjang)
    $loans = Loan::with(['user', 'details.item'])
                ->latest()
                ->get();

    return view('admin.loans.index', compact('loans'));
}

    // 2. Menyetujui peminjaman
    public function approve($id)
{
        $loan = \App\Models\Loan::findOrFail($id);

        // Langsung ubah status menjadi Approved.
        // HAPUS SEMUA LOGIKA DECREMENT STOK KARENA KITA PAKAI TIME-BASED CAPACITY
        $loan->update(['status' => 'Approved']);

        return redirect()->back()->with('success', 'Transaksi peminjaman berhasil disetujui.');
    }

    // Fungsi baru: Menyerahkan barang fisik ke warga
    public function handover($id)
    {
        $loan = Loan::findOrFail($id);
        
        // Ubah status peminjaman menjadi Active (Sedang Dipinjam)
        $loan->update(['status' => 'Active']);

        return redirect()->back()->with('success', 'Barang telah diserahkan secara fisik. Status berubah menjadi Sedang Dipinjam.');
    }

    // 3. Menolak peminjaman
    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'Rejected']);

        return redirect()->back()->with('success', 'Pengajuan peminjaman telah ditolak.');
    }

    // 4. Proses Pengembalian Barang & Cek Denda
    public function returnItem($id)
    {
        $loan = \App\Models\Loan::findOrFail($id);
        
        // Logika denda keterlambatan (Tetap dipertahankan)
        $now = \Carbon\Carbon::now();
        $dueDate = \Carbon\Carbon::parse($loan->due_date);
        $lateDays = $now->greaterThan($dueDate) ? $dueDate->diffInDays($now) + 1 : 0;

        if ($lateDays > 0) {
            \App\Models\Penalty::create([
                'loan_id' => $loan->id,
                'amount' => $lateDays * 10000,
                'payment_status' => 'Unpaid'
            ]);
        }

        // Ubah status jadi Returned
        $loan->update([
            'status' => 'Returned',
            'return_date' => $now
        ]);

        // HAPUS LOGIKA INCREMENT STOK. Biarkan kosong di sini.

        return redirect()->back()->with('success', 'Barang kembali. Status berubah menjadi Dikembalikan.');
    }

    public function payPenalty($id)
    {
        // 1. Cari data denda berdasarkan ID
        $penalty = \App\Models\Penalty::findOrFail($id);

        // 2. Ubah statusnya menjadi 'Paid' (Lunas)
        $penalty->update([
            'payment_status' => 'Paid'
            // Catatan: Jika di tabel penalties kamu punya kolom 'paid_at' (tanggal bayar), 
            // kamu bisa menambahkannya di sini: 'paid_at' => \Carbon\Carbon::now()
        ]);

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Pembayaran denda sebesar Rp ' . number_format($penalty->amount, 0, ',', '.') . ' telah berhasil dikonfirmasi dan dilunasi.');
    }

    public function penaltyReport()
    {
        // 1. Ambil HANYA denda yang sudah LUNAS (Paid)
        $penalties = \App\Models\Penalty::with(['loan.user'])
                        ->where('payment_status', 'Paid')
                        ->orderBy('updated_at', 'desc')
                        ->get();

        $totalIncome = $penalties->sum('amount');

        // 2. Ambil Barang Terlambat (Status Active & Lewat Tanggal)
        $overdueLoans = \App\Models\Loan::with(['details.item', 'user'])
                        ->where('status', 'Active') 
                        ->where('due_date', '<', now())
                        ->get();

        // 3. Kirim ke View (TIDAK ADA dd SAMA SEKALI DI SINI)
        return view('admin.reports.penalties', compact('penalties', 'totalIncome', 'overdueLoans'));
    }
}   