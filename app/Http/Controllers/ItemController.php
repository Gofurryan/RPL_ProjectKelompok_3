<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengubah latest() menjadi orderBy('id', 'desc')
        $items = Item::orderBy('item_code', 'asc')->get();

        $items = \App\Models\Item::orderBy('item_code', 'asc')->paginate(5);
        
        // Mengirim data $items ke file admin/items/index.blade.php
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
            'category' => 'required|in:Elektronik,Furniture,Perlengkapan Ibadah,Sarana Umum',
            'status' => 'required|in:Available,Borrowed,Maintenance,Damaged',
            'location' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        // LOGIKA AUTO-GENERATE KODE BARANG (Format: ITM-001, ITM-002, dst)
        // Ambil data barang yang terakhir kali dibuat
        $lastItem = Item::latest('id')->first();
        $items = Item::latest()->paginate(5); // Ubah menjadi 5 agar sesuai desain
        
        if ($lastItem && str_starts_with($lastItem->item_code, 'ITM-')) {
            // Jika ada barang sebelumnya, ambil 3 digit angkanya lalu tambah 1
            $lastNumber = (int) substr($lastItem->item_code, 4); 
            $newNumber = $lastNumber + 1;
            $newCode = 'ITM-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        } else {
            // Jika database masih benar-benar kosong, mulai dari nomor 1
            $newCode = 'ITM-001';
        }

        // 2. Simpan ke database
        Item::create([
            'item_code' => $newCode, // Masukkan kode otomatis ke sini
            'name' => $request->name,
            'category' => $request->category,
            'location' => $request->location,
            'stock' => $request->stock,
            'status' => 'Available', // Status default
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan dengan kode otomatis: ' . $newCode);
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
            'category' => 'required|in:Elektronik,Furniture,Perlengkapan Ibadah,Sarana Umum',
            'status' => 'required|in:Available,Borrowed,Maintenance,Damaged',
            'location' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

         // 2. Simpan perubahan ke database
        $item->update([
            'name' => $request->name,
            'category' => $request->category,
            'location' => $request->location,
            'stock' => $request->stock,
            'condition' => $request->condition, // Jika ada pilihan kondisi di form
            'status' => $request->status,       // Jika ada pilihan status di form
        ]);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // 1. Hapus barang dari database
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus dari sistem.');

        // 2. Kembalikan ke halaman daftar barang
        return redirect()->route('items.index');
    }
}
