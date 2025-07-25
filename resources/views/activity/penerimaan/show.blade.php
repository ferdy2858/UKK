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
            <div class="text-base font-medium mt-1">Detail Penerimaan</div>
        </div>
    </div>

    {{-- Detail --}}
    <div class="rounded-b-xl bg-white shadow -mt-1 overflow-hidden p-6 space-y-6">
        {{-- Info Umum --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="font-medium">Tanggal:</label>
                <div class="mt-1">{{ $penerimaan->tanggal->format('d-m-Y') }}</div>
            </div>
            <div>
                <label class="font-medium">Supplier:</label>
                <div class="mt-1">{{ $penerimaan->supplier->nama_supplier ?? '-' }}</div>
            </div>
        </div>

        {{-- Keterangan --}}
        <div>
            <label class="font-medium">Keterangan:</label>
            <div class="mt-1 text-gray-700">{{ $penerimaan->keterangan ?: '-' }}</div>
        </div>

        {{-- Detail Produk --}}
        <div>
            <h3 class="font-semibold text-lg mb-2">Detail Produk</h3>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="border p-2">Produk</th>
                            <th class="border p-2 w-24 text-right">Qty</th>
                            <th class="border p-2 w-32 text-right">Harga Satuan</th>
                            <th class="border p-2 w-32 text-right">Subtotal</th>
                            <th class="border p-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalQty = 0;
                            $totalHarga = 0;
                        @endphp

                        @foreach ($penerimaan->details as $detail)
                            @php
                                $subtotal = $detail->qty * $detail->harga_satuan;
                                $totalQty += $detail->qty;
                                $totalHarga += $subtotal;
                            @endphp
                            <tr>
                                <td class="border p-2">{{ $detail->produk->nama_produk ?? '-' }}</td>
                                <td class="border p-2 text-right">{{ $detail->qty }}</td>
                                <td class="border p-2 text-right">Rp {{ number_format($detail->harga_satuan, 2, ',', '.') }}</td>
                                <td class="border p-2 text-right">Rp {{ number_format($subtotal, 2, ',', '.') }}</td>
                                <td class="border p-2">{{ $detail->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-semibold">
                        <tr>
                            <td class="border p-2 text-left" colspan="1">Total</td>
                            <td class="border p-2 text-right">{{ $totalQty }}</td>
                            <td class="border p-2"></td>
                            <td class="border p-2 text-right">Rp {{ number_format($totalHarga, 2, ',', '.') }}</td>
                            <td class="border p-2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('penerimaan.edit', $penerimaan->id) }}"
               class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600" title="edit">
                <i class="fa-solid fa-pen"></i>
            </a>
            <form action="{{ route('penerimaan.destroy', $penerimaan->id) }}" method="POST"
                  onsubmit="return confirm('Yakin hapus data ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" title="hapus">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
@endsection
