<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateStok extends Model
{
    protected $fillable = [
        'product_id',
        'jumlah_produk',
        'tanggal',
        'gula_id', // ✅ tambahkan ini
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ✅ relasi gula
    public function gula()
    {
        return $this->belongsTo(\App\Models\StokBahan::class, 'gula_id');
    }
}
