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
        $items = Item::latest()->get();
        
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
            'item_code' => 'required|string|unique:items,item_code',
            'name' => 'required|string|max:255',
            'category' => 'required|in:Elektronik,Furniture,Perlengkapan Ibadah,Sarana Umum',
            'status' => 'required|in:Available,Borrowed,Maintenance,Damaged',
            'location' => 'nullable|string|max:255',
        ]);

        // 2. Simpan ke database
        Item::create($request->all()); // Cara cepat menyimpan semua data

        // 3. Kembalikan Admin ke halaman daftar barang
        return redirect()->route('items.index');
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
            // Pengecualian unique agar bisa update tanpa harus ganti item_code
            'item_code' => 'required|string|unique:items,item_code,' . $item->id, 
            'name' => 'required|string|max:255',
            'category' => 'required|in:Elektronik,Furniture,Perlengkapan Ibadah,Sarana Umum',
            'status' => 'required|in:Available,Borrowed,Maintenance,Damaged',
            'location' => 'nullable|string|max:255',
        ]);

         // 2. Simpan perubahan ke database
        $item->update($request->all());

        // 3. Kembali ke daftar barang
        return redirect()->route('items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // 1. Hapus barang dari database
        $item->delete();

        // 2. Kembalikan ke halaman daftar barang
        return redirect()->route('items.index');
    }
}
