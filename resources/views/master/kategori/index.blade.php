@extends('layouts.app')

@section('content')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <!-- Kiri: Tambah + Aksi -->
            <div class="flex items-center gap-2">
                <!-- Tombol Tambah -->
                <button onclick="openModal()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                    <span class="mr-1"><i class="fa-solid fa-plus"></i></span>
                    <h1>tambah data</h1>
                </button>

                <!-- Tombol Aksi -->
                <div id="topActionButtons" class="hidden flex gap-2">
                    <a id="editBtn" href="#" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600 text-center w-10"
                        title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form id="deleteForm" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" id="deleteBtn" class="bg-red-500 text-white p-2 rounded hover:bg-red-600 w-10"
                        title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#3085d6'
                    });
                </script>
            @endif

            <!-- Kanan: Pencarian -->
            <input type="text" id="searchInput" placeholder="Search..."
                class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring w-1/3">
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-blue-300">
                    <tr>
                        <th class="p-2 border w-16">No</th>
                        <th class="p-2 border">Kategori</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($kategoris as $kategori)
                        <tr onclick="selectRow(this)" data-id="{{ $kategori->id }}"
                            data-nama="{{ $kategori->nama_kategori }}" class="cursor-pointer hover:bg-gray-100 transition">
                            <td class="p-2 border">{{ $loop->iteration }}</td>
                            <td class="p-2 border">{{ $kategori->nama_kategori }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-gray-500">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
                <p class="text-sm text-gray-600 mb-2">
                    Menampilkan {{ $kategoris->firstItem() }} - {{ $kategoris->lastItem() }} dari total
                    {{ $kategoris->total() }} data
                </p>
            </table>
            <!-- Pagination -->
            <div class="mt-4">
                {{ $kategoris->appends(request()->query())->links() }}
            </div>
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
                    <input type="text" name="nama_kategori" id="edit_nama" required
                        class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 rounded border text-gray-700" title="batal"><i class="fa-solid fa-x"></i></button>
                    <button type="submit"
                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600" title="update"><i class="fa-solid fa-file"></i></button>
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
            selectedNama = row.dataset.nama;

            document.getElementById('topActionButtons').classList.remove('hidden');
            document.getElementById('editBtn').onclick = openEditModal;

            document.getElementById('deleteForm').action = `/kategori/${selectedId}`;
            document.getElementById('deleteBtn').classList.remove('hidden');
        }

        document.addEventListener('click', function(e) {
            const isRow = e.target.closest('tr');
            const isAction = e.target.closest('#topActionButtons');
            const isModal = e.target.closest('#editModal') || e.target.closest('#kategoriModal');
            if (!isRow && !isAction && !isModal) {
                document.getElementById('topActionButtons').classList.add('hidden');
            }
        });

        // SweetAlert konfirmasi hapus
        document.getElementById('deleteBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data kategori akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        });
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
@endsection
