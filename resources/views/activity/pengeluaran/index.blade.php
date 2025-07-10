@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto mt-6 bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <!-- Kiri: Tambah + Aksi -->
            <div class="flex items-center gap-2">
                <a href="{{ route('pengeluaran.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                    <span class="mr-1">+</span> tambah pengeluaran
                </a>

                <div id="topActionButtons" class="hidden flex gap-2">
                    <a id="viewBtn" href="#" class="bg-green-500 text-white p-2 rounded hover:bg-green-600"
                        title="Lihat">
                        🔍
                    </a>
                    <a id="editBtn" href="#" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600 hidden"
                        title="Edit">
                        ✏️
                    </a>
                    <form id="deleteForm" method="POST" class="hidden" onsubmit="return confirm('Hapus pengeluaran ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white p-2 rounded hover:bg-red-600" title="Hapus">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif


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
            </table>
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

            if (status === 'draft') {
                editBtn.classList.remove('hidden');
                deleteForm.classList.remove('hidden');

                editBtn.href = `/pengeluaran/${selectedId}/edit`;
                deleteForm.action = `/pengeluaran/${selectedId}`;
            } else {
                editBtn.classList.add('hidden');
                deleteForm.classList.add('hidden');
            }
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
