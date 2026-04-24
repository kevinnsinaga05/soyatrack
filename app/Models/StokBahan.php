<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBahan extends Model
{
    protected $fillable = [
        'nama_bahan',
        'kategori',
        'total_stok',
        'satuan',
        'stok_minimum',
        'harga_beli_per_satuan',
        'track_expired',
    ];

    protected $casts = [
        'total_stok' => 'float',
        'stok_minimum' => 'float',
        'harga_beli_per_satuan' => 'float',
        'track_expired' => 'boolean',
    ];

    public function batches()
    {
        return $this->hasMany(StokBatch::class);
    }

    public function mutations()
    {
        return $this->hasMany(StokMutation::class);
    }
}
