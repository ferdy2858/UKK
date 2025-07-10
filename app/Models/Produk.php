<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori_id',
        'stok',
        'satuan',
        'gambar_produk',
        'keterangan',
    ];

    // Relasi ke kategori (sudah oke)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // 🔄 Relasi ke detail_penerimaan
    public function detailPenerimaan()
    {
        return $this->hasMany(DetailPenerimaan::class);
    }
}
