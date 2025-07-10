<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluarannController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengeluarans = Pengeluaran::with('details')->orderBy('tanggal', 'desc')->get();
        return view('activity.pengeluaran.index', compact('pengeluarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua produk untuk dipilih di dropdown
        $produks = Produk::all();
        return view('activity.pengeluaran.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tujuan' => 'required|string',
            'status' => 'required|in:draft,disetujui,dibatalkan',
            'produk_id.*' => 'required|exists:produk,id',
            'qty.*' => 'required|integer|min:1',
            'harga_satuan.*' => 'nullable|numeric|min:0',
        ]);

        // Cek stok dulu kalau status disetujui
        if ($request->status === 'disetujui') {
            foreach ($request->produk_id as $i => $produkId) {
                $produk = Produk::findOrFail($produkId);
                $qty = $request->qty[$i];

                if ($produk->stok < $qty) {
                    return back()->withInput()->withErrors([
                        'stok' => "Stok produk '{$produk->nama_produk}' tidak mencukupi (tersisa {$produk->stok}, diminta {$qty})."
                    ]);
                }
            }
        }

        // Simpan header pengeluaran
        $pengeluaran = Pengeluaran::create([
            'tanggal' => $request->tanggal,
            'tujuan' => $request->tujuan,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        // Simpan detail dan update stok (kalau disetujui)
        foreach ($request->produk_id as $i => $produkId) {
            $qty = $request->qty[$i];
            $produk = Produk::findOrFail($produkId);

            if ($request->status === 'disetujui') {
                $produk->stok -= $qty;
                $produk->save();
            }

            $pengeluaran->details()->create([
                'produk_id' => $produkId,
                'qty' => $qty,
                'harga_satuan' => $request->harga_satuan[$i] ?? null,
                'keterangan' => $request->keterangan_detail[$i] ?? null,
            ]);
        }
        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pengeluaran = Pengeluaran::with('details.produk')->findOrFail($id);
        return view('activity.pengeluaran.show', compact('pengeluaran'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pengeluaran = Pengeluaran::with('details.produk')->findOrFail($id);

        if ($pengeluaran->status === 'disetujui') {
            return redirect()->route('pengeluaran.index')->with('error', 'Data pengeluaran yang sudah disetujui tidak dapat diedit.');
        }
        $produks = Produk::all();

        return view('activity.pengeluaran.edit', compact('pengeluaran', 'produks'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengeluaran = Pengeluaran::with('details')->findOrFail($id);

        if ($pengeluaran->status === 'disetujui') {
            return redirect()->route('pengeluaran.index')->with('error', 'Data pengeluaran yang sudah disetujui tidak dapat diubah.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'tujuan' => 'required|string',
            'status' => 'required|in:draft,disetujui',
            'produk_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        $pengeluaran = Pengeluaran::with('details')->findOrFail($id);
        $statusLama = $pengeluaran->status;
        $statusBaru = $request->status;

        // ========== CEK STOK (hanya jika status disetujui) ==========
        if ($statusBaru === 'disetujui') {
            foreach ($request->produk_id as $i => $produkId) {
                $produk = Produk::find($produkId);
                $qtyBaru = (int) $request->qty[$i];

                if ($statusLama === 'draft') {
                    // Belum pernah dikurangi stok
                    if ($produk->stok < $qtyBaru) {
                        return back()->with('error', "Stok produk '{$produk->nama_produk}' tidak mencukupi. Sisa: {$produk->stok}");
                    }
                } else {
                    // Sudah pernah dikurangi, ambil qty lama
                    $detailLama = $pengeluaran->details->firstWhere('produk_id', $produkId);
                    $qtyLama = $detailLama ? $detailLama->qty : 0;
                    $stokRollback = $produk->stok + $qtyLama;

                    if ($stokRollback < $qtyBaru) {
                        return back()->with('error', "Stok produk '{$produk->nama_produk}' tidak mencukupi setelah update. Sisa: {$stokRollback}");
                    }
                }
            }
        }

        // ========== ROLLBACK STOK jika status sebelumnya disetujui ==========
        if ($statusLama === 'disetujui') {
            foreach ($pengeluaran->details as $detail) {
                $produk = $detail->produk;
                $produk->stok += $detail->qty;
                $produk->save();
            }
        }

        // ========== UPDATE HEADER ==========
        $pengeluaran->update([
            'tanggal' => $request->tanggal,
            'tujuan' => $request->tujuan,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        // ========== HAPUS DETAIL LAMA ==========
        $pengeluaran->details()->delete();

        // ========== SIMPAN DETAIL BARU ==========
        foreach ($request->produk_id as $i => $produkId) {
            $qty = (int) $request->qty[$i];
            $hargaSatuan = $request->harga_satuan[$i] ?? null;
            $keteranganDetail = $request->keterangan_detail[$i] ?? null;

            $pengeluaran->details()->create([
                'produk_id' => $produkId,
                'qty' => $qty,
                'harga_satuan' => $hargaSatuan,
                'keterangan' => $keteranganDetail,
            ]);

            // Kurangi stok jika status disetujui
            if ($statusBaru === 'disetujui') {
                $produk = Produk::find($produkId);
                $produk->stok -= $qty;
                $produk->save();
            }
        }

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
