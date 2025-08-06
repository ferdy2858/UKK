@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <div class="bg-blue-300 text-black px-6 py-4 flex items-center justify-between rounded-t-xl">
        <div>
            <div class="text-2xl font-bold flex items-center gap-2">
                <a href="{{ route('produk.index') }}" class="bg-blue-200   px-3 py-1 rounded hover:bg-blue-300">
                    &larr;
                </a>
                Produk
            </div>
            <div class="text-base font-medium mt-1">Edit Data Produk</div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="rounded-b-xl bg-white shadow -mt-1 overflow-hidden">
        <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data"
            class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            @method('PUT')

            {{-- Kode Produk --}}
            <div>
                <label class="block font-semibold mb-1">Kode Produk <span class="text-red-500">*</span></label>
                <input type="text" name="kode_produk" value="{{ old('kode_produk', $produk->kode_produk) }}" required
                    class="w-full border px-3 py-2 rounded" />
            </div>

            {{-- Nama Produk --}}
            <div>
                <label class="block font-semibold mb-1">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required
                    class="w-full border px-3 py-2 rounded" />
            </div>

            {{-- Stok & Satuan --}}
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-semibold mb-1">Stok</label>
                    <input type="number" value="{{ $produk->stok }}" readonly
                        class="w-full border px-3 py-2 rounded bg-gray-100" name="stok">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Satuan</label>
                    <input type="text" value="{{ $produk->satuan }}" readonly
                        class="w-full border px-3 py-2 rounded bg-gray-100" name="satuan">
                </div>
            </div>

            {{-- Kategori --}}
            <div>
                <label for="kategori_id" class="block font-medium mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori_id" id="kategori_id" class="w-full border px-3 py-2 rounded" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategoris as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $produk->kategori_id ? 'selected' : '' }}>
                            {{ $item->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Upload Gambar --}}
            <div>
                <label class="block font-medium mb-1">Gambar Produk</label>
                <div class="flex items-center gap-4">
                    <input type="file" name="gambar_produk" id="gambar_produk" accept="image/*"
                        class="border px-3 py-2 rounded w-full" onchange="preparePreview(event)">
                    <button type="button" id="previewBtn" onclick="openPreviewModal()"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 {{ $produk->gambar_produk ? '' : 'hidden' }}">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="md:col-span-2">
                <label for="keterangan" class="block font-medium mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="4" class="w-full md:w-1/2 border px-3 py-2 rounded"
                    placeholder="Masukkan deskripsi...">{{ old('keterangan', $produk->keterangan) }}</textarea>
            </div>

            {{-- Tombol Simpan --}}
            <div class="col-span-full flex justify-end mt-4">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                    <i class="fa-solid fa-file"></i>Update
                </button>
            </div>
        </form>
    </div>

    {{-- Modal Preview Gambar --}}
    <div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded shadow-lg max-w-2xl w-full relative p-4 pt-10">
            <div class="flex justify-end mb-2">
                <button onclick="closePreviewModal()"
                    class="text-gray-500 hover:text-black text-xl font-bold absolute top-2 right-3">
                    ✕
                </button>
            </div>
            <img id="modalPreviewImage"
                src="{{ $produk->gambar_produk ? asset('storage/' . $produk->gambar_produk) : '' }}"
                class="w-full h-auto rounded">
        </div>
    </div>

    {{-- Script --}}
    <script>
        let imageSrc = document.getElementById('modalPreviewImage').src;

        function preparePreview(event) {
            const file = event.target.files[0];
            const previewBtn = document.getElementById('previewBtn');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageSrc = e.target.result;
                    document.getElementById('modalPreviewImage').src = imageSrc;
                    previewBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                imageSrc = '';
                document.getElementById('modalPreviewImage').src = '';
                previewBtn.classList.add('hidden');
            }
        }

        function openPreviewModal() {
            if (imageSrc) {
                document.getElementById('imagePreviewModal').classList.remove('hidden');
            }
        }

        function closePreviewModal() {
            document.getElementById('imagePreviewModal').classList.add('hidden');
        }
    </script>
@endsection
