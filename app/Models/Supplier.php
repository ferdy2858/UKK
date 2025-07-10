<?php

namespace App\Models;

use App\Models\Penerimaan;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'no_telp',
        'gmail'
    ];
    
    public function penerimaan()
    {
        return $this->hasMany(Penerimaan::class);
    }
}
