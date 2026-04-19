<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Helper Fungsi: Untuk mencegah redundansi logika Filter Pencarian
     * Digunakan oleh fungsi index() dan export()
     */
    private function applyFilters(Request $request)
    {
        $query = Item::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('item_code', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    public function index(Request $request)
    {
        // 1. Panggil Helper Filter, urutkan, dan paginasi
        $items = $this->applyFilters($request)->orderBy('item_code', 'asc')->paginate(5);

        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'status' => 'required',
            'stock' => 'required|integer|min:0',
            'condition' => 'nullable|in:good,fair,poor',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/items', $filename);
            $data['image'] = $filename;
        }

        $data['item_code'] = $this->generateItemCode();

        // [BUG FIXED]: Simpan ke variabel $item (Berbentuk Object Model)
        $item = Item::create($data);

        // [BUG FIXED]: Panggil name dari Object $item, bukan Array $data
        \App\Models\ActivityLog::record("Menambahkan inventaris baru: " . $item->name, $item);
        
        return redirect()->route('items.index')->with('success', 'Barang baru berhasil ditambahkan ke inventaris!');
    }

    private function generateItemCode()
    {
        $lastItem = Item::where('item_code', 'like', 'ITM-%')
                        ->orderBy('id', 'desc')
                        ->first();

        if (!$lastItem || !$lastItem->item_code) {
            return 'ITM-001';
        }

        $lastCode = $lastItem->item_code;
        $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastCode);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return 'ITM-' . $newNumber;
    }

    public function edit(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'status' => 'required',
            'stock' => 'required|integer|min:0',
            'condition' => 'nullable|in:good,fair,poor',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['image']); 

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::delete('public/items/' . $item->image);
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/items', $filename);
            $data['image'] = $filename; 
        }

        $item->update($data);
        \App\Models\ActivityLog::record("Memperbarui data inventaris: " . $item->name, $item);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Item $item)
    {
        $itemName = $item->name;
        $item->delete();
        \App\Models\ActivityLog::record("Menghapus inventaris: " . $itemName);
        
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus dari sistem.');
    }

    public function export(Request $request)
    {
        // 1. Panggil Helper Filter (Sangat Bersih dan Tidak Redundan)
        $items = $this->applyFilters($request)->orderBy('item_code', 'asc')->get();

        $filename = "Export_Inventaris_" . date('Y-m-d_H-i') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID Barang', 'Nama Barang', 'Kategori', 'Status', 'Stok', 'Kondisi', 'Lokasi']);

            $conditionMap = ['good' => 'Baik', 'fair' => 'Layak', 'poor' => 'Buruk'];
            $statusMap = ['Available' => 'Tersedia', 'Borrowed' => 'Dipinjam', 'Maintenance' => 'Maintenance', 'Damaged' => 'Rusak'];

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->item_code,
                    $item->name,
                    $item->category,
                    $statusMap[$item->status] ?? $item->status,
                    $item->stock,
                    $conditionMap[$item->condition] ?? $item->condition,
                    $item->location ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}