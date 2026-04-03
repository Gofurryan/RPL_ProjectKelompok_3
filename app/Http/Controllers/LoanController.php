<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input dari Warga
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'loan_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after:loan_date',
        ]);

        $itemId = $request->item_id;
        $startDate = $request->loan_date;
        $endDate = $request->due_date;

        // 2. CONFLICT DETECTION LOGIC (Cek Bentrok Jadwal)
        $isConflict = Loan::where('item_id', $itemId)
            ->whereIn('status', ['Approved', 'Active']) // Abaikan yang statusnya Pending/Rejected/Returned
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('loan_date', [$startDate, $endDate])
                      ->orWhereBetween('due_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('loan_date', '<=', $startDate)
                            ->where('due_date', '>=', $endDate);
                      });
            })
            ->exists(); // Mengembalikan nilai true jika bentrok, false jika aman

        if ($isConflict) {
            // Jika jadwal bentrok, kembalikan ke halaman sebelumnya dengan error
            return back()->withErrors(['conflict' => 'Maaf, barang ini sudah dibooking pada rentang tanggal tersebut. Silakan pilih tanggal lain.'])->withInput();
        }

        // Jika aman, simpan ke database dengan status 'Pending'
        Loan::create([
            'user_id' => Auth::id(),
            'item_id' => $itemId,
            'loan_date' => $startDate,
            'due_date' => $endDate,
            'status' => 'Pending', // Menunggu disetujui Petugas/Ketua Takmir
        ]);

        return redirect()->route('warga.dashboard')->with('success', 'Booking berhasil diajukan! Menunggu persetujuan petugas.');
    }
    // ==========================================
    // AREA ADMIN / PETUGAS
    // ==========================================
    
    // 1. Menampilkan daftar semua pengajuan
    public function adminIndex()
    {
        $loans = Loan::with(['user', 'item'])->latest()->get();
        return view('admin.loans.index', compact('loans'));
    }

    // 2. Menyetujui peminjaman
    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'Approved']);
        $loan->item->update(['status' => 'Borrowed']);

        return redirect()->back()->with('success', 'Pengajuan peminjaman disetujui. Barang otomatis Dipinjam.');
    }

    // 3. Menolak peminjaman
    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'Rejected']);

        return redirect()->back()->with('success', 'Pengajuan peminjaman telah ditolak.');
    }
}