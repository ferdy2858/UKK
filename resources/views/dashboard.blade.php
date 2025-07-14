@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6 space-y-6">

        {{-- Ringkasan Kartu --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-gray-500">Total Produk</div>
                <div class="text-2xl font-bold">{{ $totalProduk }}</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-gray-500">Total Stok</div>
                <div class="text-2xl font-bold">{{ $totalStok }}</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-gray-500">Penerimaan Hari Ini</div>
                <div class="text-2xl font-bold">{{ $penerimaanHariIni }}</div>
            </div>
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="text-gray-500">Pengeluaran Hari Ini</div>
                <div class="text-2xl font-bold">{{ $pengeluaranHariIni }}</div>
            </div>
        </div>

        {{-- Grafik Penerimaan vs Pengeluaran --}}
        <div class="bg-white p-6 shadow rounded-lg">
            <div class="text-lg font-semibold mb-4">Grafik Penerimaan & Pengeluaran (7 Hari Terakhir)</div>
            <canvas id="stokChart" height="100"></canvas>
        </div>

        {{-- Pengeluaran Terbaru --}}
        <div class="bg-white p-6 shadow rounded-lg">
            <div class="flex justify-between mb-4">
                <h2 class="text-lg font-semibold">Pengeluaran Terbaru</h2>
                <a href="{{ route('pengeluaran.index') }}" class="text-blue-600 hover:underline text-sm">Lihat semua</a>
            </div>
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border text-left">Tanggal</th>
                        <th class="p-2 border text-left">Tujuan</th>
                        <th class="p-2 border text-center">Qty</th>
                        <th class="p-2 border text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengeluaranTerbaru as $p)
                        <tr>
                            <td class="p-2 border">{{ $p->tanggal->format('d-m-Y') }}</td>
                            <td class="p-2 border">{{ $p->tujuan }}</td>
                            <td class="p-2 border text-center">{{ $p->details->sum('qty') }}</td>
                            <td class="p-2 border text-center">
                                <span class="px-2 py-1 rounded text-white text-xs {{ $p->status === 'disetujui' ? 'bg-green-500' : 'bg-gray-500' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-gray-500 text-center">Tidak ada pengeluaran baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Produk Stok Menipis --}}
        <div class="bg-white p-6 shadow rounded-lg">
            <div class="flex justify-between mb-4">
                <h2 class="text-lg font-semibold">Stok Hampir Habis</h2>
                <a href="{{ route('produk.index') }}" class="text-blue-600 hover:underline text-sm">Lihat semua produk</a>
            </div>
            <table class="w-full text-sm border">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-2 border">Produk</th>
                        <th class="p-2 border text-right">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stokMenipis as $produk)
                        <tr>
                            <td class="p-2 border">{{ $produk->nama_produk }}</td>
                            <td class="p-2 border text-right text-red-600 font-semibold">{{ $produk->stok }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-gray-500 text-center">Stok aman semua.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('stokChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($grafikTanggal) !!},
                datasets: [
                    {
                        label: 'Penerimaan',
                        backgroundColor: '#4ade80',
                        data: {!! json_encode($grafikPenerimaan) !!}
                    },
                    {
                        label: 'Pengeluaran',
                        backgroundColor: '#f87171',
                        data: {!! json_encode($grafikPengeluaran) !!}
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    </script>
@endsection
