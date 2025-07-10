@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <!-- Kiri: Tambah + Aksi -->
        <div class="flex items-center gap-2">
            <!-- Tombol Tambah -->
            <button onclick="openModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                <span class="mr-1">+</span>
                <h1>tambah data</h1>
            </button>

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
                    <th class="p-2 border">Kategori</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse ($kategoris as $kategori)
                    <tr onclick="selectRow(this)"
                        data-id="{{ $kategori->id }}"
                        data-kategori="{{ $kategori->kategori }}"
                        class="cursor-pointer hover:bg-gray-100 transition">
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $kategori->nama_kategori }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-4 text-gray-500">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div id="kategoriModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Tambah Kategori</h2>
        <form method="POST" action="{{ route('kategori.store') }}" id="kategoriForm">
            @csrf
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama" required
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 rounded border text-gray-700">Batal</button>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Edit Kategori</h2>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_nama" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="kategori" id="edit_nama" required
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 rounded border text-gray-700">Batal</button>
                <button type="submit"
                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script>
    let selectedId = null;
    let selectedNama = null;

    function openModal() {
        document.getElementById('kategoriModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('kategoriForm').reset();
        document.getElementById('kategoriModal').classList.add('hidden');
    }

    function openEditModal() {
        document.getElementById('edit_nama').value = selectedNama;
        document.getElementById('editForm').action = `/kategori/${selectedId}`;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editForm').reset();
        document.getElementById('editModal').classList.add('hidden');
    }

    function selectRow(row) {
        selectedId = row.dataset.id;
        selectedNama = row.dataset.kategori;

        document.getElementById('topActionButtons').classList.remove('hidden');
        document.getElementById('viewBtn').href = `#`;
        document.getElementById('deleteForm').action = `/kategori/${selectedId}`;
        document.getElementById('editBtn').onclick = openEditModal;
    }

    document.addEventListener('click', function (e) {
        const isRow = e.target.closest('tr');
        const isAction = e.target.closest('#topActionButtons');
        const isEditModal = e.target.closest('#editModal');
        if (!isRow && !isAction && !isEditModal) {
            document.getElementById('topActionButtons').classList.add('hidden');
        }
    });
</script>
@endsection
