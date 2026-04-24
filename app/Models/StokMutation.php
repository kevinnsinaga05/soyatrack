<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMutation extends Model
{
    protected $fillable = [
        'stok_bahan_id',
        'stok_batch_id',
        'tanggal',
        'tipe',
        'qty',
        'satuan',
        'source',
        'source_key',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'qty' => 'float',
    ];

    public function stokBahan()
    {
        return $this->belongsTo(StokBahan::class);
    }

    public function stokBatch()
    {
        return $this->belongsTo(StokBatch::class);
    }

    public function batchMutations()
    {
        return $this->hasMany(StokBatchMutation::class);
    }
}
