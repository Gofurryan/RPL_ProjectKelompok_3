<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Loan;

class LoanTable extends Component
{
    // Menggunakan template pagination bawaan Tailwind
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    
    // Variabel penampung teks ketikan Admin
    public $search = '';

    public function mount()
    {
        // Ambil data 'search' dari URL jika ada, lalu masukkan ke variabel $search
        $this->search = request()->query('search', $this->search);
    }

    // Otomatis kembali ke halaman 1 jika Admin mengetik sesuatu
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Cari data berdasarkan nama user, email user, atau status peminjaman
        $loans = Loan::with(['user', 'details.item', 'penalty'])
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orWhere('status', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10); // Tampilkan 10 data per halaman

        return view('livewire.admin.loan-table', compact('loans'));
    }
}