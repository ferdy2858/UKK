<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPenerimaan extends Model
{
    use HasFactory;

    protected $table = 'detail_penerimaan';

    protected $fillable = [
        'penerimaan_id',
        'produk_id',
        'qty',
        'harga_satuan',
        'keterangan',
    ];

    protected $casts = [
        'harga_satuan' => 'float',
        'qty' => 'integer',
    ];

    // RELASI
    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
