@extends('layouts.app')

@section('content')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
            <!-- Kiri: Tambah + Aksi + Filter -->
            <div class="flex items-center gap-3 flex-wrap w-full">
                <!-- Tombol Tambah -->
                <a href="{{ route('pengeluaran.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                    <span class="mr-1"><i class="fa-solid fa-plus"></i></span> tambah pengeluaran
                </a>

                <!-- Tombol Aksi -->
                <div id="topActionButtons" class="hidden flex gap-2">
                    <a id="viewBtn" href="#"
                        class="bg-green-500 text-white p-2 rounded hover:bg-green-600 text-center w-10" title="Lihat">
                        <i class="fa-solid fa-magnifying-glass"></i></a>
                    <a id="editBtn" href="#"
                        class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600 hidden text-center w-10"
                        title="Edit"><i class="fa-solid fa-pen"></i></a>

                    <form id="deleteForm" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="deleteBtn"
                            class="bg-red-500 text-white p-2 rounded hover:bg-red-600 w-10" title="Hapus">
                            <i class="fa-solid fa-trash"></i></button>
                    </form>

                    <form id="approveForm" method="POST" class="hidden">
                        @csrf
                        @method('PATCH')
                        <button type="button" id="approveBtn"
                            class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 w-10" title="Setujui">
                            <i class="fa-solid fa-check"></i></button>
                    </form>
                </div>

                <!-- Filter Status - DIKANANKAN -->
                <form method="GET" class="ml-auto">
                    <div class="flex items-center gap-1">
                        <label for="status" class="text-sm text-gray-600">Status:</label>
                        <select name="status" id="status" onchange="this.form.submit()"
                            class="border border-gray-300 rounded px-2 py-1 text-sm">
                            <option value="">Semua</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                        </select>
                    </div>
                </form>
                <!-- Kanan: Pencarian -->
                <input type="text" id="searchInput" placeholder="Search..."
                    class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring w-1/3">
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

            @if (session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'OPPS',
                        text: {!! json_encode(session('error')) !!},
                        confirmButtonColor: '#d33'
                    });
                </script>
            @endif
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 border w-16">No</th>
                        <th class="p-2 border">Tanggal</th>
                        <th class="p-2 border">Tujuan</th>
                        <th class="p-2 border">Jumlah Item</th>
                        <th class="p-2 border">Total Qty</th>
                        <th class="p-2 border">Total Harga</th>
                        <th class="p-2 border">Keterangan</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($pengeluarans as $pengeluaran)
                        <tr onclick="selectRow(this)" class="cursor-pointer hover:bg-gray-100 transition"
                            data-id="{{ $pengeluaran->id }}" data-status="{{ $pengeluaran->status }}">
                            <td class="p-2 border">{{ $loop->iteration }}</td>
                            <td class="p-2 border">{{ $pengeluaran->tanggal->format('d-m-Y') }}</td>
                            <td class="p-2 border">{{ $pengeluaran->tujuan }}</td>
                            <td class="p-2 border">{{ $pengeluaran->details->count() }}</td>
                            <td class="p-2 border">{{ $pengeluaran->details->sum('qty') }}</td>
                            <td class="p-2 border">
                                Rp
                                {{ number_format($pengeluaran->details->sum(fn($d) => $d->qty * $d->harga_satuan), 0, ',', '.') }}
                            </td>
                            <td class="p-2 border">{{ $pengeluaran->keterangan ?? '-' }}</td>
                            <td class="p-2 border capitalize">{{ $pengeluaran->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-4 text-gray-500">Belum ada data pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
                <p class="text-sm text-gray-600 mb-2">
                    Menampilkan {{ $pengeluarans->firstItem() }} - {{ $pengeluarans->lastItem() }} dari total
                    {{ $pengeluarans->total() }} data
                </p>
            </table>
            <!-- Pagination -->
            <div class="mt-4">
                {{ $pengeluarans->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        let selectedId = null;

        function selectRow(row) {
            selectedId = row.dataset.id;
            const status = row.dataset.status;

            document.getElementById('topActionButtons').classList.remove('hidden');
            document.getElementById('viewBtn').href = `/pengeluaran/${selectedId}`;

            const editBtn = document.getElementById('editBtn');
            const deleteForm = document.getElementById('deleteForm');
            const approveForm = document.getElementById('approveForm');

            if (status === 'draft') {
                editBtn.classList.remove('hidden');
                deleteForm.classList.remove('hidden');
                approveForm.classList.remove('hidden');

                editBtn.href = `/pengeluaran/${selectedId}/edit`;
                deleteForm.action = `/pengeluaran/${selectedId}`;
                approveForm.action = `/pengeluaran/${selectedId}/approve`;
            } else {
                editBtn.classList.add('hidden');
                deleteForm.classList.add('hidden');
                approveForm.classList.add('hidden');
            }
        }

        document.getElementById('approveBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Setujui pengeluaran ini?',
                text: "Data akan dikunci dan tidak bisa diedit atau dihapus!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approveForm').submit();
                }
            });
        });


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
                text: "Data pengeluaran akan dihapus permanen!",
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
