<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\Loan;

class GlobalSearch extends Component
{
    public $search = '';

    public function render()
{
    $results = ['items' => [], 'loans' => []];

    if (strlen($this->search) > 2) {
        // Cari Barang (Bisa dilihat siapa saja)
        $results['items'] = Item::where('name', 'like', '%' . $this->search . '%')->limit(3)->get();

        // Cari Transaksi (Dibatasi berdasarkan Role)
        $query = Loan::with(['user', 'details.item']);

        if (auth()->user()->role == 'petugas') {
            // Admin bisa cari semua nama peminjam
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        } else {
            // Warga HANYA bisa cari di dalam riwayat mereka sendiri
            $query->where('user_id', auth()->id())
                  ->whereHas('details.item', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }

        $results['loans'] = $query->limit(3)->get();
    }

    return view('livewire.global-search', compact('results'));
}
}