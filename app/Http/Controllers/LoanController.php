<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Item;
use App\Models\Penalty;
use App\Models\LoanDetail;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    // ==========================================
    // AREA WARGA (Peminjam)
    // ==========================================
    public function dashboardWarga()
    {
        $userId = auth()->id();

        $activeLoan = Loan::with('details.item')
                        ->where('user_id', $userId)
                        ->whereIn('status', ['Pending', 'Approved', 'Active'])
                        ->first();

        $activeLoansCount = $activeLoan ? 1 : 0;
        $returnedLoansCount = Loan::where('user_id', $userId)->where('status', 'Returned')->count();
        $unpaidPenalties = Penalty::whereHas('loan', function($q) use ($userId) {
                                        $q->where('user_id', $userId);
                                    })->where('payment_status', 'Unpaid')->sum('amount');
        
        $recentLoans = Loan::with('details.item')
                           ->where('user_id', $userId)
                           ->orderBy('created_at', 'desc')
                           ->take(5)
                           ->get();

        return view('warga.dashboard', compact('activeLoan', 'activeLoansCount', 'returnedLoansCount', 'unpaidPenalties', 'recentLoans'));
    }

    public function createWarga()
    {
        $items = Item::whereIn('status', ['Available', 'Borrowed'])->get();
        return view('warga.booking', compact('items'));
    }

    public function historyWarga()
    {
        $loans = Loan::where('user_id', auth()->id())
                    ->with(['details.item', 'penalty'])
                    ->latest()
                    ->get();
        return view('warga.history', compact('loans'));
    }

    // ==========================================
    // PROSES PEMINJAMAN (Booking)
    // ==========================================
    public function create()
    {
        $items = Item::where('stock', '>', 0)->get();
        return view('loans.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
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

        // Cek Konflik Transaksi Berjalan
        if (Loan::where('user_id', $user->id)->whereIn('status', ['Pending', 'Approved', 'Active'])->exists()) {
            return back()->withErrors(['conflict' => 'Sistem Menolak: Anda masih memiliki transaksi peminjaman yang sedang berjalan.'])->withInput();
        }

        // Cek Denda atau Keterlambatan
        $hasOverdue = Loan::where('user_id', $user->id)->where('status', 'Active')->where('due_date', '<', Carbon::now())->exists();
        $hasUnpaidPenalty = Penalty::whereHas('loan', function($q) use ($user) { $q->where('user_id', $user->id); })->where('payment_status', 'Unpaid')->exists();

        if ($hasOverdue || $hasUnpaidPenalty) {
            return back()->withErrors(['conflict' => 'Sistem Menolak (Blacklist): Anda memiliki keterlambatan atau denda belum lunas.'])->withInput();
        }

        // Batas Kuota (10 Barang)
        if (collect($request->items)->sum('qty') > 10) {
            return back()->withErrors(['conflict' => 'Batas Kuota: Maksimal total barang yang dipinjam adalah 10 unit per transaksi.'])->withInput();
        }

        try {
            // [BUG FIXED]: Meneruskan $loan keluar dari block closure transaction
            $loan = DB::transaction(function () use ($request) {
                $startDate = $request->loan_date;
                $endDate = $request->due_date;

                $loan = Loan::create([
                    'user_id' => auth()->id(),
                    'loan_date' => $startDate,
                    'due_date' => $endDate,
                    'status' => 'Pending',
                ]);

                foreach ($request->items as $itemData) {
                    $itemId = $itemData['id'];
                    $qtyRequested = $itemData['qty'];
                    $item = Item::findOrFail($itemId);

                    $bookedCount = LoanDetail::where('item_id', $itemId)
                        ->whereHas('loan', function ($q) use ($startDate, $endDate) {
                            $q->whereIn('status', ['Approved', 'Active'])
                              ->where(function ($q2) use ($startDate, $endDate) {
                                  $q2->whereBetween('loan_date', [$startDate, $endDate])
                                     ->orWhereBetween('due_date', [$startDate, $endDate])
                                     ->orWhere(function ($q3) use ($startDate, $endDate) {
                                         $q3->where('loan_date', '<=', $startDate)->where('due_date', '>=', $endDate);
                                     });
                              });
                        })->sum('quantity');

                    if (($bookedCount + $qtyRequested) > $item->stock) {
                        $sisa = $item->stock - $bookedCount;
                        throw new \Exception("Stok tidak mencukupi untuk '{$item->name}'. (Sisa kuota: {$sisa} unit).");
                    }

                    LoanDetail::create([
                        'loan_id' => $loan->id,
                        'item_id' => $itemId,
                        'quantity' => $qtyRequested,
                    ]);
                }
                
                return $loan; // Kembalikan data $loan
            });

            // [BUG FIXED]: Sekarang $loan bisa dibaca dengan aman di sini
            ActivityLog::record("Mengajukan peminjaman baru", $loan);

            // Menyatukan pesan menjadi 1 string kuat agar tidak bentrok dengan Blade
            $pesanSukses = "Peminjaman berhasil diajukan! Barang yang disetujui wajib diambil maksimal dalam 2 hari.";
            return redirect()->route('warga.history')->with('success', $pesanSukses);

        } catch (\Exception $e) {
            return back()->withErrors(['conflict' => $e->getMessage()])->withInput();
        }
    }

    // ==========================================
    // AREA ADMIN / PETUGAS
    // ==========================================
    public function adminIndex()
    {
        $loans = Loan::with(['user', 'details.item'])->latest()->get();
        return view('admin.loans.index', compact('loans'));
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'Approved']);
        ActivityLog::record("Menyetujui peminjaman milik " . $loan->user->name, $loan);

        return redirect()->back()->with('success', 'Transaksi peminjaman berhasil disetujui.');
    }

    public function handover($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'Active']);
        
        // [TAMBAHAN]: Memasukkan Log untuk Handover
        ActivityLog::record("Menyerahkan barang peminjaman kepada " . $loan->user->name, $loan);

        return redirect()->back()->with('success', 'Barang telah diserahkan secara fisik. Status berubah menjadi Sedang Dipinjam.');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'Rejected']);
        ActivityLog::record("Menolak peminjaman milik " . $loan->user->name, $loan);

        return redirect()->back()->with('success', 'Pengajuan peminjaman telah ditolak.');
    }

    public function returnItem($id)
    {
        $loan = Loan::findOrFail($id);
        $now = Carbon::now();
        $dueDate = Carbon::parse($loan->due_date);
        $lateDays = $now->greaterThan($dueDate) ? $dueDate->diffInDays($now) + 1 : 0;

        if ($lateDays > 0) {
            Penalty::create([
                'loan_id' => $loan->id,
                'amount' => $lateDays * 10000,
                'payment_status' => 'Unpaid'
            ]);
        }

        $loan->update([
            'status' => 'Returned',
            'return_date' => $now
        ]);

        // [BUG FIXED]: Menambahkan ActivityLog pada saat barang dikembalikan
        ActivityLog::record("Menerima pengembalian barang dari " . $loan->user->name, $loan);

        return redirect()->back()->with('success', 'Barang kembali. Status berubah menjadi Dikembalikan.');
    }

    public function payPenalty($id)
    {
        $penalty = Penalty::findOrFail($id);
        $penalty->update(['payment_status' => 'Paid']);

        ActivityLog::record("Menerima pelunasan denda keterlambatan sebesar Rp " . number_format($penalty->amount, 0, ',', '.'), $penalty);
        
        return redirect()->back()->with('success', 'Pembayaran denda sebesar Rp ' . number_format($penalty->amount, 0, ',', '.') . ' telah berhasil dikonfirmasi dan dilunasi.');
    }

    public function penaltyReport()
    {
        $penalties = Penalty::with(['loan.user'])->where('payment_status', 'Paid')->orderBy('updated_at', 'desc')->get();
        $totalIncome = $penalties->sum('amount');
        
        $overdueLoans = Loan::with(['details.item', 'user'])->where('status', 'Active')->where('due_date', '<', now())->get();

        return view('admin.reports.penalties', compact('penalties', 'totalIncome', 'overdueLoans'));
    }
}