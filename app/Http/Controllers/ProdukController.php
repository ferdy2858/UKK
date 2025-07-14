<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->paginate(20);
        return view('master.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('master.produk.create', compact('kategoris'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|string|unique:produk',
            'nama_produk' => 'required|string|max:150',
            'stok' => 'required',
            'satuan' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('gambar_produk')) {
            $validated['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        Produk::create($validated);
        
        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
{
    return view('master.produk.show', compact('produk'));
}


    public function edit(Produk $produk)
    {
        $kategoris = Kategori::all();
        return view('master.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|string|unique:produk,kode_produk,' . $produk->id,
            'nama_produk' => 'required|string|max:150',
            'stok' => 'required',
            'satuan' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('gambar_produk')) {
            $validated['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
