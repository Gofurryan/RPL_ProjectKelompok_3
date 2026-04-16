<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // 1. Mulai query baru (kosong) untuk model Item
    $query = \App\Models\Item::query();

    // 2. Filter Pencarian Teks (Berdasarkan Nama atau Kode Barang)
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('item_code', 'like', '%' . $request->search . '%');
        });
    }

    // 3. Filter Kategori
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    // 4. Filter Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 5. Eksekusi query HANYA SATU KALI di sini dengan sorting dan batasan 5 per halaman
    $items = $query->orderBy('item_code', 'asc')->paginate(5);

    // 6. Return dilakukan SATU KALI saja di paling bawah fungsi
    // (Pastikan path view-nya sesuai dengan folder aslimu, di sini saya pakai admin.items.index)
    return view('admin.items.index', compact('items'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi inputan agar tidak ada data kosong/ngawur
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'status' => 'required',
            'stock' => 'required|integer|min:0',
            'condition' => 'nullable|in:good,fair,poor',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Siapkan Data untuk Disimpan
        $data = $request->all();

        // 3. Logika Unggah Foto
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Buat nama unik: timestamp_nama-asli.ext
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Simpan ke folder: storage/app/public/items
            $file->storeAs('public/items', $filename);
            
            // Simpan nama filenya saja ke kolom 'image' di database
            $data['image'] = $filename;
        }

        // 4. Generate Kode Barang Otomatis (ITM-001, dst)
        $data['item_code'] = $this->generateItemCode();

        // 5. Simpan ke Database (Hanya dipanggil SATU KALI)
        Item::create($data);

        // 6. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Barang baru berhasil ditambahkan ke inventaris!');
    }

    /**
     * Fungsi pembantu untuk generate kode barang otomatis (Tahan Banting)
     */
    private function generateItemCode()
    {
        // 1. Ambil barang terakhir, TAPI HANYA yang kodenya berawalan 'ITM-'
        // Ini akan otomatis mengabaikan barang siluman seperti 'INV-PER-001'
        $lastItem = \App\Models\Item::where('item_code', 'like', 'ITM-%')
                                    ->orderBy('id', 'desc')
                                    ->first();

        // 2. Jika benar-benar belum ada data berawalan ITM-, mulai dari ITM-001
        if (!$lastItem || !$lastItem->item_code) {
            return 'ITM-001';
        }

        // 3. Ambil kode terakhir
        $lastCode = $lastItem->item_code;
        
        // 4. EKSTRAK HANYA ANGKA menggunakan Regex (Paling Aman!)
        // Jika kodenya 'ITM-005', ini akan mengambil '005' lalu menjadikannya angka 5
        $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastCode);

        // 5. Tambahkan 1, lalu format ulang menjadi 3 digit (contoh: 5 + 1 = 006)
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        // 6. Kembalikan kode baru
        return 'ITM-' . $newNumber;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, item $item)
    {
        // 1. Validasi inputan baru
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'status' => 'required',
            'stock' => 'required|integer|min:0',
            'condition' => 'nullable|in:good,fair,poor',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Ambil semua data kecuali gambar
    $data = $request->except(['image']); 

    // 3. Logika Ganti Gambar
    if ($request->hasFile('image')) {
        // Hapus gambar lama dari server jika ada
        if ($item->image) {
            Storage::delete('public/items/' . $item->image);
        }

        // Simpan gambar baru
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/items', $filename);
        
        // Masukkan nama gambar baru ke data yang akan diupdate
        $data['image'] = $filename; 
    }

    // 4. EKSEKUSI UPDATE (Perhatikan: Menggunakan $item->update, BUKAN Item::create)
    $item->update($data);

    // 5. Kembali ke halaman utama
    return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // 1. Hapus barang dari database
        $item->delete();
        
        // 2. Kembalikan ke halaman daftar barang dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus dari sistem.');
    }

    /**
     * Export Data Barang ke Format CSV (Excel)
     */
    public function export(Request $request) // Tambahkan parameter Request $request
{
    // 1. Inisialisasi query seperti di fungsi index
    $query = \App\Models\Item::query();

    // 2. Terapkan Filter yang sama dengan halaman daftar barang
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

    // 3. Ambil data hasil filter
    $items = $query->orderBy('item_code', 'asc')->get();

    // --- Sisa kode streaming CSV tetap sama ---
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