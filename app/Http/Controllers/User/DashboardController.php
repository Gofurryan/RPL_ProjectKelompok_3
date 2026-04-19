<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Loan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Fungsi bantuan untuk menghitung persentase tren naik/turun
     */
    private function calculateTrend($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0; 
        }
        return round((($current - $previous) / $previous) * 100);
    }

    public function index(Request $request) 
    {
        // Waktu untuk perhitungan tren (Bulan Ini vs Bulan Lalu)
        $now = Carbon::now();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // ==========================================
        // 1. STATISTIK KARTU ATAS & TREN PERTUMBUHAN
        // ==========================================
        $totalItems = Item::count();
        $availableItems = Item::where('status', 'Available')->count();
        $borrowedItems = Item::where('status', 'Borrowed')->count();
        $maintenanceItems = Item::where('status', 'Maintenance')->count();

        // Tren Total Barang
        $totalItemsLastMonth = Item::where('created_at', '<=', $endOfLastMonth)->count();
        $trendTotal = $this->calculateTrend($totalItems, $totalItemsLastMonth);

        // Tren Peminjaman
        $loansThisMonth = Loan::whereMonth('created_at', $now->month)->count();
        $loansLastMonth = Loan::whereMonth('created_at', $now->subMonth()->month)->count();
        $trendBorrowed = $this->calculateTrend($loansThisMonth, $loansLastMonth);

        // Tren Tersedia & Maintenance (Simulasi Statis)
        $trendAvailable = -2; 
        $trendMaintenance = 0; 

        // ==========================================
        // 2. AKTIVITAS TERBARU (Menggunakan ActivityLog)
        // ==========================================
        // Kode lama yang menggabungkan 3 tabel sudah DIHAPUS.
        // Sekarang murni mengambil 5 data dari tabel logs.
        $latestActivities = \App\Models\ActivityLog::with('user')
                            ->latest()
                            ->take(5)
                            ->get();

        // ==========================================
        // 3. LOGIKA GRAFIK PEMINJAMAN 
        // ==========================================
        $range = $request->query('range', 7); 
        $startDate = Carbon::now()->subDays($range)->startOfDay();
        $chartLoans = Loan::where('created_at', '>=', $startDate)->get();

        $chartData = ['Sen' => 0, 'Sel' => 0, 'Rab' => 0, 'Kam' => 0, 'Jum' => 0, 'Sab' => 0, 'Min' => 0];
        $dayMap = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min'];

        foreach ($chartLoans as $loan) {
            $dayIndex = Carbon::parse($loan->created_at)->dayOfWeekIso;
            $chartData[$dayMap[$dayIndex]]++;
        }

        $maxCount = max($chartData) > 0 ? max($chartData) : 1; 
        $currentDay = $dayMap[Carbon::now()->dayOfWeekIso];

        // ==========================================
        // 4. DATA STOK PER KATEGORI (Ambil 4 Teratas)
        // ==========================================
        $categories = Item::select('category', \DB::raw('count(*) as total'))
                          ->groupBy('category')
                          ->orderByDesc('total')
                          ->take(4)
                          ->get();
                          
        $maxCategoryCount = $categories->max('total') > 0 ? $categories->max('total') : 1;

        // ==========================================
        // 5. DATA STATUS BARANG (Kondisi)
        // ==========================================
        $kondisiBaik = Item::where('condition', 'good')->count();
        $kondisiServis = Item::where('condition', 'fair')->count(); 
        $kondisiRusak = Item::where('condition', 'poor')->count();
        
        $totalKondisi = $totalItems > 0 ? $totalItems : 1; 

        $pctBaik = round(($kondisiBaik / $totalKondisi) * 100);
        $pctServis = round(($kondisiServis / $totalKondisi) * 100);
        $pctRusak = round(($kondisiRusak / $totalKondisi) * 100);

        // ==========================================
        // RETURN VIEW DENGAN COMPACT YANG BERSIH
        // ==========================================
        return view('admin.dashboard', compact(
            'totalItems', 'availableItems', 'borrowedItems', 'maintenanceItems',
            'trendTotal', 'trendAvailable', 'trendBorrowed', 'trendMaintenance',
            'latestActivities', 'chartData', 'maxCount', 'currentDay', 'range',
            'categories', 'maxCategoryCount', 'kondisiBaik', 'kondisiServis', 'kondisiRusak', 'pctBaik', 'pctServis', 'pctRusak'
        ));
    }
}