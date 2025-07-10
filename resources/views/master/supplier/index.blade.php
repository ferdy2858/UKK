@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <!-- Kiri: Tambah + Aksi -->
            <div class="flex items-center gap-2">
                <!-- Tombol Tambah -->
                <a href="{{ route('supplier.create') }}"
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
                        <th class="p-2 border">Nama Supplier</th>
                        <th class="p-2 border">Alamat</th>
                        <th class="p-2 border">No Telp</th>
                        <th class="p-2 border">Gmail</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($suppliers as $supplier)
                        <tr onclick="selectRow(this)" data-id="{{ $supplier->id }}" data-nama="{{ $supplier->nama_supplier }}"
                            class="cursor-pointer hover:bg-gray-100 transition">
                            <td class="p-2 border">{{ $loop->iteration }}</td>
                            <td class="p-2 border">{{ $supplier->nama_supplier }}</td>
                            <td class="p-2 border">{{ $supplier->alamat }}</td>
                            <td class="p-2 border">{{ $supplier->no_telp }}</td>
                            <td class="p-2 border">{{ $supplier->gmail }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-gray-500">Belum ada supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let selectedId = null;
        let selectedNama = null;

        function selectRow(row) {
            selectedId = row.dataset.id;
            selectedNama = row.dataset.nama;

            // Tampilkan tombol aksi
            document.getElementById('topActionButtons').classList.remove('hidden');
            document.getElementById('viewBtn').href = `#`;
            document.getElementById('editBtn').href = `/supplier/${selectedId}/edit`;
            document.getElementById('deleteForm').action = `/supplier/${selectedId}`;
        }

        document.addEventListener('click', function(e) {
            const isRow = e.target.closest('tr');
            const isAction = e.target.closest('#topActionButtons');
            if (!isRow && !isAction) {
                document.getElementById('topActionButtons').classList.add('hidden');
            }
        });
    </script>
@endsection
