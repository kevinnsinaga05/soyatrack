<?php

namespace Database\Seeders;

use App\Models\StokBahan;
use Illuminate\Database\Seeder;

class PowderSeeder extends Seeder
{
    /**
     * Seed default powder options for product form.
     */
    //jenis powder
    public function run(): void
    {
        $powders = [
            'Chocolate Powder',
            'Taro Powder',
            'Matcha Powder',
            'Vanilla Powder',
            'Red Velvet Powder',
        ];

        foreach ($powders as $name) {
            StokBahan::firstOrCreate(
                ['nama_bahan' => $name],
                [
                    'kategori' => 'Powder',
                    'total_stok' => 0,
                    'stok_minimum' => 0,
                    'satuan' => 'gram',
                    'harga_beli_per_satuan' => 0,
                    'track_expired' => false,
                ]
            );
        }
    }
}
