<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBatch extends Model
{
    protected $fillable = [
        'stok_bahan_id',
        'tanggal_masuk',
        'expired_at',
        'qty_in',
        'qty_remaining',
        'harga_beli_per_satuan',
        'note',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'expired_at' => 'date',
        'qty_in' => 'float',
        'qty_remaining' => 'float',
        'harga_beli_per_satuan' => 'float',
    ];

    public function stokBahan()
    {
        return $this->belongsTo(StokBahan::class);
    }
}
