@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <div class="bg-gray-200 text-black px-6 py-4 flex items-center justify-between rounded-t-xl">
        <div>
            <div class="text-2xl font-bold flex items-center gap-2">
                <a href="{{ route('produk.index') }}" class="bg-white text-red-800 px-3 py-1 rounded hover:bg-gray-200">
                    &larr;
                </a>
                Produk
            </div>
            <div class="text-base font-medium mt-1">Detail Produk</div>
        </div>
    </div>

    {{-- Konten --}}
    <div class="rounded-b-xl bg-white shadow -mt-1 overflow-hidden p-6 space-y-6">

        {{-- Info Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-semibold">Kode Produk:</label>
                <div class="mt-1 text-gray-800">{{ $produk->kode_produk }}</div>
            </div>

            <div>
                <label class="block font-semibold">Nama Produk:</label>
                <div class="mt-1 text-gray-800">{{ $produk->nama_produk }}</div>
            </div>

            <div>
                <label class="block font-semibold">Kategori:</label>
                <div class="mt-1 text-gray-800">{{ $produk->kategori->nama_kategori ?? '-' }}</div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-semibold">Stok:</label>
                    <div class="mt-1 text-gray-800">{{ $produk->stok }}</div>
                </div>
                <div>
                    <label class="block font-semibold">Satuan:</label>
                    <div class="mt-1 text-gray-800">{{ $produk->satuan }}</div>
                </div>
            </div>
        </div>

        {{-- Keterangan --}}
        <div>
            <label class="block font-semibold">Keterangan:</label>
            <div class="mt-1 text-gray-700 whitespace-pre-line">
                {{ $produk->keterangan ?: '-' }}
            </div>
        </div>

        {{-- Gambar --}}
        <div>
            <label class="block font-semibold">Gambar Produk:</label>
            @if ($produk->gambar_produk)
                <img src="{{ asset('storage/' . $produk->gambar_produk) }}" alt="Gambar Produk"
                     class="w-60 h-60 object-cover rounded border shadow mt-2">
            @else
                <p class="text-gray-500 italic mt-2">Tidak ada gambar.</p>
            @endif
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('produk.edit', $produk->id) }}"
               class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Edit
            </a>

            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST"
                  onsubmit="return confirm('Yakin hapus data ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Hapus
                </button>
            </form>
        </div>
    </div>
@endsection
