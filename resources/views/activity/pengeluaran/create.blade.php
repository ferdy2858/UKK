@extends('layouts.app')

@section('content')
    <div class="bg-gray-200 text-black px-6 py-4 flex items-center justify-between rounded-t-xl">
        <div>
            <div class="text-2xl font-bold flex items-center gap-2">
                <a href="{{ route('pengeluaran.index') }}" class="bg-white text-red-800 px-3 py-1 rounded hover:bg-gray-200">
                    &larr;
                </a>
                Pengeluaran
            </div>
            <div class="text-base font-medium mt-1">Tambah Data Pengeluaran</div>
        </div>
    </div>

    <div class="rounded-b-xl bg-white shadow -mt-1 overflow-hidden">

        {{-- ALERT STOK TIDAK CUKUP --}}
        @if ($errors->has('stok'))
            <div class="bg-red-100 text-red-800 border border-red-400 px-4 py-3 rounded m-6">
                ⚠️ {{ $errors->first('stok') }}
            </div>
        @endif
        
        <form action="{{ route('pengeluaran.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal -->
                <div>
                    <label class="block font-semibold mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" required class="w-full border px-3 py-2 rounded"
                        value="{{ date('Y-m-d') }}" />
                </div>

                <!-- Tujuan -->
                <div>
                    <label class="block font-semibold mb-1">Tujuan <span class="text-red-500">*</span></label>
                    <input type="text" name="tujuan" required class="w-full border px-3 py-2 rounded"
                        placeholder="Tujuan pengeluaran (contoh: Produksi, Gudang, dll)">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Status</label>
                    <select name="status" class="w-full border px-3 py-2 rounded" required>
                        <option value="draft">Draft</option>
                        <option value="disetujui">Disetujui</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block font-semibold mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full border px-3 py-2 rounded"
                    placeholder="Contoh: Barang dikeluarkan untuk keperluan produksi."></textarea>
            </div>

            <hr class="my-4 border-t-2">

            <!-- Detail Barang -->
            <div>
                <h3 class="text-lg font-semibold mb-2">Detail Barang</h3>
                <div id="detailContainer" class="space-y-4">
                    <!-- Baris detail pertama -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 detail-row">
                        <select name="produk_id[]" required class="border px-3 py-2 rounded">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produks as $produk)
                                <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="qty[]" class="border px-3 py-2 rounded" placeholder="Qty" required>
                        <input type="number" step="0.01" name="harga_satuan[]" class="border px-3 py-2 rounded"
                            placeholder="Harga Satuan (Opsional)">
                        <input type="text" name="keterangan_detail[]" class="border px-3 py-2 rounded"
                            placeholder="Keterangan (opsional)">
                        <button type="button" onclick="removeRow(this)"
                            class="bg-red-500 text-white w-10 h-10 px-3 py-2 rounded">✕</button>
                    </div>
                </div>

                <button type="button" onclick="addRow()"
                    class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    + Tambah Baris
                </button>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <script>
        function addRow() {
            const container = document.getElementById('detailContainer');
            const row = container.querySelector('.detail-row');
            const clone = row.cloneNode(true);

            clone.querySelectorAll('input').forEach(input => input.value = '');
            clone.querySelector('select').value = '';
            container.appendChild(clone);
        }

        function removeRow(button) {
            const container = document.getElementById('detailContainer');
            if (container.children.length > 1) {
                button.parentElement.remove();
            }
        }
    </script>
@endsection
