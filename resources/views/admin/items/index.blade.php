<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Inventaris') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Tambah Barang Baru
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-3 px-6 text-center w-16">No</th>
                                <th class="py-3 px-6 text-left">Nama Barang</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($items as $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                                    <td class="py-4 px-6 text-center font-medium">{{ $loop->iteration }}</td>
                                    
                                    <td class="py-4 px-6 text-left font-medium text-gray-900">{{ $item->name }}</td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        @if($item->status == 'Available')
                                            <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-semibold">Tersedia</span>
                                        @elseif($item->status == 'Damaged')
                                            <span class="bg-red-100 text-red-800 py-1 px-3 rounded-full text-xs font-semibold">Rusak</span>
                                        @elseif($item->status == 'Borrowed')
                                            <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full text-xs font-semibold">Dipinjam</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 py-1 px-3 rounded-full text-xs font-semibold">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center space-x-4">
                                            <a href="{{ route('items.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold transition duration-150">Edit</a>
                                            
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini? Data tidak bisa dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold transition duration-150">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 px-6 text-center text-gray-500 font-medium">
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