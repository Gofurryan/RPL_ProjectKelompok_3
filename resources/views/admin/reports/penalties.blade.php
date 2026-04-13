<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Pemasukan Denda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="-mt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Laporan Kas & Denda</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400">Pantau riwayat pembayaran denda keterlambatan untuk transparansi dan akuntabilitas keuangan kas.</p>
</div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-green-500">
                <div class="p-6 text-gray-900 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Kas Masuk (Dari Denda)</p>
                        <h3 class="text-3xl font-extrabold text-green-600 mt-1">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 border-b border-gray-200 text-gray-600 uppercase text-xs leading-normal">
                                    <th class="py-3 px-4 text-left">Waktu Pembayaran</th>
                                    <th class="py-3 px-4 text-left">Peminjam</th>
                                    <th class="py-3 px-4 text-center">ID Transaksi</th>
                                    <th class="py-3 px-4 text-right">Nominal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @forelse($penalties as $penalty)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-4 px-4 text-left whitespace-nowrap">
                                            {{ $penalty->updated_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="py-4 px-4 text-left font-bold text-gray-800">
                                            {{ $penalty->loan->user->name }}
                                        </td>
                                        <td class="py-4 px-4 text-center text-indigo-600 font-medium">
                                            #TRX-{{ str_pad($penalty->loan->id, 4, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="py-4 px-4 text-right font-bold text-green-600">
                                            {{ number_format($penalty->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500 italic">
                                            Belum ada pemasukan dari denda keterlambatan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>