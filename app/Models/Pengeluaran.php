<?php

namespace App\Models;

use App\Models\DetailPengeluaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';

    protected $fillable = [
        'tanggal',
        'tujuan',
        'status',
        'keterangan',
    ];

    // App\Models\Pengeluaran.php
    protected $casts = [
        'tanggal' => 'date',
    ];


    public function details()
    {
        return $this->hasMany(DetailPengeluaran::class);
    }
}
