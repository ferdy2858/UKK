<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Penerimaan;
use Illuminate\Http\Request;
use App\Models\DetailPenerimaan;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penerimaans = Penerimaan::with(['supplier', 'details'  ])
        ->orderBy('tanggal', 'desc')->paginate(20);
        return view('activity.penerimaan.index', compact('penerimaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penerimaan = Penerimaan::all();
        $suppliers = Supplier::all();
        $produks = Produk::all();
        return view('activity.penerimaan.create', compact('penerimaan', 'suppliers', 'produks'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'nullable|exists:supplier,id', // pakai nama tabel kamu
            'produk_id' => 'required|array|min:1',
            'produk_id.*' => 'required|exists:produk,id',
            'qty.*' => 'required|numeric|min:1',
            'harga_satuan.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Simpan ke tabel penerimaan
            $penerimaan = Penerimaan::create([
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'keterangan' => $request->keterangan,
            ]);

            // Simpan ke tabel detail_penerimaan
            foreach ($request->produk_id as $i => $produkId) {
                $qty = $request->qty[$i];
                $harga = $request->harga_satuan[$i];
                $ketDetail = $request->keterangan_detail[$i] ?? null;

                DetailPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'produk_id' => $produkId,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'keterangan' => $ketDetail,
                ]);

                // Tambahkan stok ke produk
                $produk = Produk::find($produkId);
                if ($produk) {
                    $produk->stok += $qty;
                    $produk->save();
                }
            }

            DB::commit();

            return redirect()->route('penerimaan.index')->with('success', 'Penerimaan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $penerimaan = Penerimaan::with(['supplier', 'details.produk'])->findOrFail($id);

        return view('activity.penerimaan.show', compact('penerimaan'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penerimaan = Penerimaan::with(['details.produk'])->findOrFail($id);
        $suppliers = Supplier::all(); // kirim data supplier untuk dropdown

        return view('activity.penerimaan.edit', compact('penerimaan', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $penerimaan = Penerimaan::findOrFail($id);

            // 1. Update data utama penerimaan
            $penerimaan->update([
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'keterangan' => $request->keterangan,
            ]);

            // 2. Ambil semua detail lama
            $existingDetailIds = $penerimaan->details()->pluck('id')->toArray();
            $inputDetailIds = array_filter($request->detail_id); // hanya yang ada

            // 3. Hapus detail yang tidak dikirim lagi
            $idsToDelete = array_diff($existingDetailIds, $inputDetailIds);
            foreach ($idsToDelete as $detailId) {
                $detail = DetailPenerimaan::find($detailId);
                if ($detail) {
                    $produk = Produk::find($detail->produk_id);
                    if ($produk) {
                        $produk->stok -= $detail->qty;
                        $produk->save();
                    }
                    $detail->delete();
                }
            }

            // 4. Simpan/Update detail penerimaan
            foreach ($request->produk_id as $index => $produk_id) {
                $detailId = $request->detail_id[$index];
                $qtyBaru = $request->qty[$index];
                $hargaBaru = $request->harga_satuan[$index];
                $keteranganBaru = $request->keterangan_detail[$index] ?? null;

                $produk = Produk::find($produk_id);

                if ($detailId) {
                    $detail = DetailPenerimaan::find($detailId);
                    if ($detail && $produk) {
                        $selisih = $qtyBaru - $detail->qty;
                        $produk->stok += $selisih;
                        $produk->save();

                        $detail->update([
                            'qty' => $qtyBaru,
                            'harga_satuan' => $hargaBaru,
                            'keterangan' => $keteranganBaru,
                        ]);
                    }
                } else {
                    // Tambah baru
                    DetailPenerimaan::create([
                        'penerimaan_id' => $penerimaan->id,
                        'produk_id' => $produk_id,
                        'qty' => $qtyBaru,
                        'harga_satuan' => $hargaBaru,
                        'keterangan' => $keteranganBaru,
                    ]);

                    if ($produk) {
                        $produk->stok += $qtyBaru;
                        $produk->save();
                    }
                }
            }

            DB::commit();
            return redirect()->route('penerimaan.index')->with('success', 'Penerimaan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penerimaan = Penerimaan::with('details')->findOrFail($id);

            // Kurangi stok produk sebelum delete
            foreach ($penerimaan->details as $detail) {
                $produk = Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->stok -= $detail->qty;
                    $produk->save();
                }
            }

            // Hapus semua detail
            $penerimaan->details()->delete();

            // Hapus data utama
            $penerimaan->delete();

            DB::commit();
            return redirect()->route('penerimaan.index')->with('success', 'Data penerimaan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menghapus: ' . $e->getMessage());
        }
    }
}
