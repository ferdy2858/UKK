<?php

namespace App\Models;

use App\Models\Produk;
use App\Models\Pengeluaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPengeluaran extends Model
{
    use HasFactory;

    protected $table = 'detail_pengeluarans';

    protected $fillable = [
        'pengeluaran_id',
        'produk_id',
        'harga_satuan',
        'qty',
        'keterangan',
    ];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
