<x-app-layout>
    <div class="flex flex-col gap-6 max-w-7xl mx-auto pb-10">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Manajemen Peminjaman</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola persetujuan, serah terima, dan pengembalian barang inventaris.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <livewire:admin.loan-table />

    </div>
</x-app-layout>