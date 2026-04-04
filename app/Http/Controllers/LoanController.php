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
        $myLoans = \App\Models\Loan::where('user_id', auth()->id())->latest()->get();
        return view('warga.history', compact('myLoans'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input dari Warga
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'loan_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after:loan_date',
        ], [
            // Error
            'item_id.required' => 'Silakan pilih barang terlebih dahulu.',
            'loan_date.required' => 'Rencana tanggal diambil wajib diisi.',
            'loan_date.after_or_equal' => 'Tanggal pengambilan tidak boleh di masa lalu (minimal hari ini).',
            'due_date.required' => 'Rencana tanggal dikembalikan wajib diisi.',
            'due_date.after' => 'Tanggal pengembalian harus setelah tanggal peminjaman. *Silahkan periksa kembali.',
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

        return redirect()->route('warga.history')->with('success', 'Booking berhasil diajukan! Menunggu persetujuan petugas.');
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
        $loan = Loan::findOrFail($id);
        $now = \Carbon\Carbon::now();
        $dueDate = \Carbon\Carbon::parse($loan->due_date);
        
        // Cek apakah tanggal sekarang melewati batas waktu (due_date)
        $lateDays = 0;
        if ($now->greaterThan($dueDate)) {
            // Hitung selisih hari (dibulatkan ke atas)
            $lateDays = $dueDate->diffInDays($now) + 1; 
        }

        // Jika terlambat, buat record denda (Rp 10.000 per hari)
        $denda = 0;
        if ($lateDays > 0) {
            $denda = $lateDays * 10000;
            \App\Models\Penalty::create([
                'loan_id' => $loan->id,
                'amount' => $denda,
                'payment_status' => 'Unpaid'
            ]);
        }

        // Kembalikan status peminjaman & catat tanggal kembali aktual
        $loan->update([
            'status' => 'Returned',
            'return_date' => $now
        ]);

        // Bebaskan kembali barang di inventaris utama
        $loan->item->update(['status' => 'Available']);

        $pesan = 'Barang berhasil dikembalikan.';
        if ($lateDays > 0) {
            $pesan .= ' Peminjam TERLAMBAT ' . $lateDays . ' hari dan dikenakan denda Rp ' . number_format($denda, 0, ',', '.');
        }

        return redirect()->back()->with('success', $pesan);
    }

    // Fungsi baru: Konfirmasi pembayaran denda oleh warga
    public function payPenalty($id)
    {
        // Cari data denda berdasarkan ID-nya
        $penalty = \App\Models\Penalty::findOrFail($id);
        
        // Ubah status menjadi Paid (Lunas)
        $penalty->update(['payment_status' => 'Paid']);

        return redirect()->back()->with('success', 'Pembayaran denda berhasil dikonfirmasi. Status berubah menjadi Lunas.');
    }
}