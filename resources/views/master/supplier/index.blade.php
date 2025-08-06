@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <!-- Kiri: Tambah + Aksi -->
            <div class="flex items-center gap-2">
                <!-- Tombol Tambah -->
                <a href="{{ route('supplier.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                    <span class="mr-1"><i class="fa-solid fa-plus"></i></span> tambah data
                </a>

                <!-- Tombol Aksi (ikon) -->
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
                        <th class="p-2 border">Nama Supplier</th>
                        <th class="p-2 border">Alamat</th>
                        <th class="p-2 border">No Telp</th>
                        <th class="p-2 border">Gmail</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($suppliers as $supplier)
                        <tr onclick="selectRow(this)" data-id="{{ $supplier->id }}"
                            data-nama="{{ $supplier->nama_supplier }}" class="cursor-pointer hover:bg-gray-100 transition">
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
                <p class="text-sm text-gray-600 mb-2">
                    Menampilkan {{ $suppliers->firstItem() }} - {{ $suppliers->lastItem() }} dari total
                    {{ $suppliers->total() }} data
                </p>
            </table>
            <!-- Pagination -->
            <div class="mt-4">
                {{ $suppliers->appends(request()->query())->links() }}
            </div>
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
