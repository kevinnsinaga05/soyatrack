<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     protected $fillable = [
    'nama_produk',
    'harga_jual',
    'jumlah_susu',
    'jumlah_gula',
    'jumlah_gula_tropicana',
    'powder_id',
    'jenis_powder',
    'jumlah_powder',
];




    protected $casts = [
        'harga_jual' => 'float',
    ];

    public function packagings()
    {
        return $this->hasMany(ProductPackaging::class);
    }
}
