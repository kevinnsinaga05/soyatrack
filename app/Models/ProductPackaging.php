<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackaging extends Model
{
    protected $fillable = ['product_id','stok_bahan_id','qty_per_product'];

    public function stokBahan() {
        return $this->belongsTo(StokBahan::class);
    }
}
