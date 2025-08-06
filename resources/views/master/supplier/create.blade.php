@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <div class="bg-blue-300 text-black px-6 py-4 flex items-center justify-between rounded-t-xl">
        <div>
            <div class="text-2xl font-bold flex items-center gap-2">
                <a href="{{ route('supplier.index') }}" class="bg-blue-200   px-3 py-1 rounded hover:bg-blue-300">
                    &larr;
                </a>
                Supplier
            </div>
            <div class="text-base font-medium mt-1">Tambah Data Supplier</div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="rounded-b-xl bg-white shadow -mt-1 overflow-hidden">
        <form action="{{ route('supplier.store') }}" method="POST" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            {{-- Nama Supplier --}}
            <div>
                <label class="block font-semibold mb-1">Nama Supplier <span class="text-red-500">*</span></label>
                <input type="text" name="nama_supplier" value="{{ old('nama_supplier') }}" required
                       class="w-full border px-3 py-2 rounded @error('nama_supplier') border-red-500 @enderror" />
                @error('nama_supplier')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alamat --}}
            <div>
                <label class="block font-semibold mb-1">Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat') }}"
                       class="w-full border px-3 py-2 rounded @error('alamat') border-red-500 @enderror" />
                @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No Telp --}}
            <div>
                <label class="block font-semibold mb-1">No Telp</label>
                <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                       class="w-full border px-3 py-2 rounded @error('no_telp') border-red-500 @enderror" />
                @error('no_telp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Gmail --}}
            <div>
                <label class="block font-semibold mb-1">Gmail</label>
                <input type="email" name="gmail" value="{{ old('gmail') }}"
                       class="w-full border px-3 py-2 rounded @error('gmail') border-red-500 @enderror" />
                @error('gmail')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Simpan --}}
            <div class="col-span-full flex justify-end mt-4">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
