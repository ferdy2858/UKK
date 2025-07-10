@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <!-- Kiri: Tambah + Aksi -->
            <div class="flex items-center gap-2">
                <!-- Tombol Tambah -->
                <a href="{{ route('produk.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                    <span class="mr-1">+</span> tambah data
                </a>
                
                <!-- Tombol Aksi (ikon) -->
                <div id="topActionButtons" class="hidden flex gap-2">
                    <a id="viewBtn" href="#" class="bg-green-500 text-white p-2 rounded hover:bg-green-600" title="Lihat">
                        🔍
                    </a>
                    <a id="editBtn" href="#" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600" title="Edit">
                        ✏️
                    </a>
                    <form id="deleteForm" method="POST" onsubmit="return confirm('Hapus penerimaan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white p-2 rounded hover:bg-red-600" title="Hapus">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kanan: Pencarian -->
            <input type="text" id="searchInput" placeholder="Search..."
                class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring w-1/3">
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 border w-16">No</th>
                        <th class="p-2 border">Nama Produk</th>
                        <th class="p-2 border">Kategori</th>
                        <th class="p-2 border">Gambar</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($produks as $produk)
                        <tr onclick="selectRow(this)" data-id="{{ $produk->id }}" data-produk="{{ $produk->nama_produk }}"
                            class="cursor-pointer hover:bg-gray-100 transition">
                            <td class="p-2 border">{{ $loop->iteration }}</td>
                            <td class="p-2 border">{{ $produk->nama_produk }}</td>
                            <td class="p-2 border">{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                            <td class="p-2 border">
                                @if ($produk->gambar_produk)
                                    <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="Gambar"
                                        class="w-12 h-12 object-cover rounded mx-auto">
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-gray-500">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script -->
    <script>
        let selectedId = null;
        let selectedNama = null;

        function selectRow(row) {
            selectedId = row.dataset.id;
            selectedNama = row.dataset.produk;

            document.getElementById('topActionButtons').classList.remove('hidden');
            document.getElementById('viewBtn').href = `/produk/${selectedId}`;
            document.getElementById('deleteForm').action = `/produk/${selectedId}`;
            document.getElementById('editBtn').href = `/produk/${selectedId}/edit`;
        }

        document.addEventListener('click', function(e) {
            const isRow = e.target.closest('tr');
            const isAction = e.target.closest('#topActionButtons');
            const isEditModal = e.target.closest('#editModal');
            if (!isRow && !isAction && !isEditModal) {
                document.getElementById('topActionButtons').classList.add('hidden');
            }
        });
    </script>
@endsection
