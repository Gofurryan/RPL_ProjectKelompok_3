<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ActivityLog;

class DashboardApiController extends Controller
{
    /**
     * API 1: Mendapatkan Statistik Dashboard
     * URL: GET /api/dashboard/statistics
     */
    public function statistics()
    {
        try {
            // 1. Hitung Statistik Barang
            $totalItems = Item::count();
            $availableItems = Item::where('status', 'Available')->count();
            $borrowedItems = Item::where('status', 'Borrowed')->count();
            $maintenanceItems = Item::where('status', 'Maintenance')->count();

            // 2. Hitung Kondisi Barang
            $goodCondition = Item::where('condition', 'good')->count();
            $fairCondition = Item::where('condition', 'fair')->count();
            $poorCondition = Item::where('condition', 'poor')->count();

            // Return response format JSON
            return response()->json([
                'success' => true,
                'message' => 'Data statistik berhasil diambil',
                'data' => [
                    'inventory' => [
                        'total' => $totalItems,
                        'available' => $availableItems,
                        'borrowed' => $borrowedItems,
                        'maintenance' => $maintenanceItems,
                    ],
                    'conditions' => [
                        'good' => $goodCondition,
                        'fair' => $fairCondition,
                        'poor' => $poorCondition,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API 2: Mendapatkan Activity Logs
     * URL: GET /api/dashboard/activities
     */
    public function activities()
    {
        try {
            // Ambil 10 log aktivitas terbaru beserta nama pelakunya
            $logs = ActivityLog::with('user:id,name')
                               ->latest()
                               ->take(10)
                               ->get()
                               ->map(function ($log) {
                                   return [
                                       'id' => $log->id,
                                       'actor' => $log->user->name ?? 'Sistem',
                                       'description' => $log->description,
                                       'ip_address' => $log->ip_address,
                                       'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                                       'time_ago' => $log->created_at->diffForHumans(),
                                   ];
                               });

            return response()->json([
                'success' => true,
                'message' => 'Data activity log berhasil diambil',
                'data' => $logs
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}