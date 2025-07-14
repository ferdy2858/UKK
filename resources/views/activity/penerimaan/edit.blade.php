@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <div class="bg-gray-200 text-black px-6 py-4 flex items-center justify-between rounded-t-xl">
        <div>
            <div class="text-2xl font-bold flex items-center gap-2">
                <a href="{{ route('penerimaan.index') }}" class="bg-white text-red-800 px-3 py-1 rounded hover:bg-gray-200">
                    &larr;
                </a>
                Penerimaan
            </div>
            <div class="text-base font-medium mt-1">Edit Penerimaan</div>
        </div>
    </div>

    {{-- Form --}}
    <div class="rounded-b-xl bg-white shadow -mt-1 overflow-hidden">
        <form action="{{ route('penerimaan.update', $penerimaan->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Tanggal & Supplier --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal"
                        value="{{ old('tanggal', optional($penerimaan->tanggal)->format('Y-m-d')) }}" required
                        class="w-full border px-3 py-2 rounded">

                </div>

                <div>
                    <label class="block font-semibold mb-1">Supplier</label>
                    <select name="supplier_id" class="w-full border px-3 py-2 rounded">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ $supplier->id == $penerimaan->supplier_id ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Keterangan Umum --}}
            <div>
                <label class="block font-semibold mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full border px-3 py-2 rounded"
                    placeholder="Keterangan umum penerimaan">{{ old('keterangan', $penerimaan->keterangan) }}</textarea>
            </div>

            {{-- Tabel Detail Penerimaan --}}
            <div>
                <h3 class="font-semibold text-lg mb-2">Detail Produk</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-left">
                            <tr>
                                <th class="border p-2">Produk</th>
                                <th class="border p-2 w-28">Qty</th>
                                <th class="border p-2 w-40">Harga Satuan</th>
                                <th class="border p-2">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penerimaan->details as $i => $detail)
                                <tr>
                                    <td class="border p-2">
                                        <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                        <input type="hidden" name="produk_id[]" value="{{ $detail->produk_id }}">
                                        <input type="text" value="{{ $detail->produk->nama_produk ?? '—' }}"
                                            class="w-full bg-gray-100 border px-2 py-1 rounded" readonly>
                                    </td>
                                    <td class="border p-2">
                                        <input type="number" name="qty[]" value="{{ old('qty.' . $i, $detail->qty) }}"
                                            class="w-full border px-2 py-1 rounded" required>
                                    </td>
                                    <td class="border p-2">
                                        <input type="number" name="harga_satuan[]" step="0.01"
                                            value="{{ old('harga_satuan.' . $i, $detail->harga_satuan) }}"
                                            class="w-full border px-2 py-1 rounded" required>
                                    </td>
                                    <td class="border p-2">
                                        <input type="text" name="keterangan_detail[]"
                                            value="{{ old('keterangan_detail.' . $i, $detail->keterangan) }}"
                                            class="w-full border px-2 py-1 rounded">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600">
                    <i class="fa-solid fa-file"></i>Update Penerimaan  
                </button>
            </div>
        </form>
    </div>
@endsection
