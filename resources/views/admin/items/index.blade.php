<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Inventaris') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            <div class="mb-6">
                <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Tambah Barang Baru
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-3 px-4 text-center w-12">No</th>
                                <th class="py-3 px-4 text-left">Kode</th>
                                <th class="py-3 px-4 text-left">Nama Barang</th>
                                <th class="py-3 px-4 text-center">Kategori</th>
                                <th class="py-3 px-4 text-center">Lokasi</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-center">Kondisi</th>
                                <th class="py-3 px-4 text-center">Stok</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($items as $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                                    <td class="py-4 px-4 text-center font-medium">{{ $loop->iteration }}</td>
                                    <td class="py-4 px-4 text-left font-medium text-indigo-600">{{ $item->item_code }}</td>
                                    <td class="py-4 px-4 text-left font-medium text-gray-900">{{ $item->name }}</td>
                                    <td class="py-4 px-4 text-center">{{ $item->category }}</td>
                                    <td class="py-4 px-4 text-center">{{ $item->location ?? '-' }}</td>
                                    <td class="py-4 px-4 text-center">
                                        @if($item->status == 'Available')
                                            <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Tersedia</span>
                                        @elseif($item->status == 'Damaged')
                                            <span class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-xs font-semibold">Rusak</span>
                                        @elseif($item->status == 'Borrowed')
                                            <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full text-xs font-semibold">Dipinjam</span>
                                        @elseif($item->status == 'Maintenance')
                                            <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-semibold">Pemeliharaan</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($item->condition == 'good')
                                            <span class="bg-teal-100 text-teal-800 py-1 px-3 rounded-md text-xs font-bold tracking-wide uppercase">Baik</span>
                                        @elseif($item->condition == 'fair')
                                            <span class="bg-orange-100 text-orange-800 py-1 px-3 rounded-md text-xs font-bold tracking-wide uppercase">Layak</span>
                                        @elseif($item->condition == 'poor')
                                            <span class="bg-rose-100 text-rose-800 py-1 px-3 rounded-md text-xs font-bold tracking-wide uppercase">Buruk</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 py-1 px-3 rounded-md text-xs font-bold tracking-wide uppercase">{{ $item->condition ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($item->stock <= 0)
                                            <span class="text-red-600 font-bold">0 (Habis)</span>
                                        @elseif($item->stock <= 2)
                                            <span class="text-orange-500 font-bold">{{ $item->stock }} (Hampir Habis)</span>
                                        @else
                                            <span class="text-gray-700">{{ $item->stock }}</span>
                                        @endif
                                    </td>
                                        </td>
                                    <td class="py-4 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('items.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold transition duration-150">Edit</a>
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold transition duration-150">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 px-6 text-center text-gray-500 font-medium">
                                        Belum ada data barang di inventaris.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>