@extends('layouts.app')

@section('content')
    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <!-- Kiri: Tambah + Aksi -->
            <div class="flex items-center gap-2">
                <a href="{{ route('penerimaan.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                    <span class="mr-1"><i class="fa-solid fa-plus"></i></span> tambah penerimaan
                </a>
                @role('admin')
                    <div id="topActionButtons" class="hidden flex gap-2">
                        <a id="viewBtn" href="#"
                            class="bg-green-500 text-white p-2 rounded hover:bg-green-600 text-center w-10" title="Lihat">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </a>
                        <a id="editBtn" href="#"
                            class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600 text-center w-10" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form id="deleteForm" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                            <button type="button" id="deleteBtn"
                                class="bg-red-500 text-white p-2 rounded hover:bg-red-600 w-10" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @endrole
                @role('staf gudang')
                    <div id="topActionButtons" class="hidden flex gap-2">
                        <a id="viewBtn" href="#"
                            class="bg-green-500 text-white p-2 rounded hover:bg-green-600 text-center w-10" title="Lihat">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </a>
                        <a id="editBtn" href="#"
                            class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600 text-center w-10" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form id="deleteForm" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                            <button type="button" id="deleteBtn"
                                class="bg-red-500 text-white p-2 rounded hover:bg-red-600 w-10" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @endrole
            </div>

            <!-- Kanan: Pencarian -->
            <input type="text" id="searchInput" placeholder="Search..."
                class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring w-1/3">
        </div>

        <!-- SweetAlert success flash -->
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: @json(session('success')),
                        confirmButtonColor: '#3085d6'
                    });
                });
            </script>
        @endif

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-blue-300">
                    <tr>
                        <th class="p-2 border w-16">No</th>
                        <th class="p-2 border">Tanggal</th>
                        <th class="p-2 border">Supplier</th>
                        <th class="p-2 border">Jumlah Item</th>
                        <th class="p-2 border">Total Qty</th>
                        <th class="p-2 border">Total Harga</th>
                        <th class="p-2 border">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($penerimaans as $penerimaan)
                        <tr onclick="selectRow(this)" class="cursor-pointer hover:bg-gray-100 transition"
                            data-id="{{ $penerimaan->id }}">
                            <td class="p-2 border">{{ $loop->iteration }}</td>
                            <td class="p-2 border">{{ $penerimaan->tanggal->format('d-m-Y') }}</td>
                            <td class="p-2 border">{{ $penerimaan->supplier->nama_supplier ?? '-' }}</td>
                            <td class="p-2 border">{{ $penerimaan->details->count() }}</td>
                            <td class="p-2 border">{{ $penerimaan->details->sum('qty') }}</td>
                            <td class="p-2 border">
                                Rp
                                {{ number_format($penerimaan->details->sum(fn($d) => $d->qty * $d->harga_satuan), 0, ',', '.') }}
                            </td>
                            <td class="p-2 border">{{ $penerimaan->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-gray-500">Belum ada data penerimaan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <p class="text-sm text-gray-600 mb-2">
                    Menampilkan {{ $penerimaans->firstItem() }} - {{ $penerimaans->lastItem() }} dari total
                    {{ $penerimaans->total() }} data
                </p>
            </table>
            <div class="mt-4">
                {{ $penerimaans->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        let selectedId = null;

        function selectRow(row) {
            selectedId = row.dataset.id;

            document.getElementById('topActionButtons').classList.remove('hidden');
            document.getElementById('viewBtn').href = `/penerimaan/${selectedId}`;
            document.getElementById('editBtn').href = `/penerimaan/${selectedId}/edit`;
            document.getElementById('deleteForm').action = `/penerimaan/${selectedId}`;
            document.getElementById('deleteForm').classList.remove('hidden'); // <= Tambah ini
        }

        document.addEventListener('click', function(e) {
            const isRow = e.target.closest('tr');
            const isAction = e.target.closest('#topActionButtons');
            if (!isRow && !isAction) {
                document.getElementById('topActionButtons').classList.add('hidden');
            }
        });

        // SweetAlert Konfirmasi Hapus
        document.getElementById('deleteBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data penerimaan akan dihapus permanen!",
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
