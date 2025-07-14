<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Statistik utama
        $totalProduk = Produk::count();
        $totalStok = Produk::sum('stok');

        $hariIni = now()->format('Y-m-d');
        $penerimaanHariIni = Penerimaan::whereDate('tanggal', $hariIni)->count();
        $pengeluaranHariIni = Pengeluaran::whereDate('tanggal', $hariIni)
            ->where('status', 'disetujui')
            ->count();


        // Ambil tanggal 7 hari terakhir
        $rangeTanggal = collect(range(0, 6))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        })->reverse();

        // Penerimaan per hari
        $penerimaanPerHari = DB::table('penerimaan')
            ->join('detail_penerimaan', 'penerimaan.id', '=', 'detail_penerimaan.penerimaan_id')
            ->selectRaw('penerimaan.tanggal, SUM(detail_penerimaan.qty) as total')
            ->whereBetween('penerimaan.tanggal', [now()->subDays(6)->format('Y-m-d'), now()->format('Y-m-d')])
            ->groupBy('penerimaan.tanggal')
            ->pluck('total', 'tanggal');

        // Pengeluaran per hari
        $pengeluaranPerHari = DB::table('pengeluarans')
            ->join('detail_pengeluarans', 'pengeluarans.id', '=', 'detail_pengeluarans.pengeluaran_id')
            ->selectRaw('pengeluarans.tanggal, SUM(detail_pengeluarans.qty) as total')
            ->where('pengeluarans.status', 'disetujui')
            ->whereBetween('pengeluarans.tanggal', [now()->subDays(6)->format('Y-m-d'), now()->format('Y-m-d')])
            ->groupBy('pengeluarans.tanggal')
            ->pluck('total', 'tanggal');

        // Data untuk grafik
        $grafikTanggal = [];
        $grafikPenerimaan = [];
        $grafikPengeluaran = [];

        foreach ($rangeTanggal as $tanggal) {
            $grafikTanggal[] = Carbon::parse($tanggal)->format('d M');
            $grafikPenerimaan[] = $penerimaanPerHari[$tanggal] ?? 0;
            $grafikPengeluaran[] = $pengeluaranPerHari[$tanggal] ?? 0;
        }

        // Pengeluaran terbaru
        $pengeluaranTerbaru = Pengeluaran::latest()->take(5)->with('details')->get();

        // Produk dengan stok menipis
        $stokMenipis = Produk::where('stok', '<', 10)->orderBy('stok', 'asc')->take(10)->get();

        return view('dashboard', compact(
            'totalProduk',
            'totalStok',
            'penerimaanHariIni',
            'pengeluaranHariIni',
            'grafikTanggal',
            'grafikPenerimaan',
            'grafikPengeluaran',
            'pengeluaranTerbaru',
            'stokMenipis'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
