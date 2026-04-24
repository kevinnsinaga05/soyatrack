<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBatchMutation extends Model
{
    protected $fillable = [
        'stok_mutation_id',
        'stok_batch_id',
        'qty',
    ];

    protected $casts = [
        'qty' => 'float',
    ];

    public function stokMutation()
    {
        return $this->belongsTo(StokMutation::class);
    }

    public function stokBatch()
    {
        return $this->belongsTo(StokBatch::class);
    }
}
