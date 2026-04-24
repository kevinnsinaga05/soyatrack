<?php

namespace App\Exports;

use App\Models\StokUpdate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        return StokUpdate::with('product')
            ->whereDate('tanggal', $this->tanggal)
            ->orderBy('product_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Produk',
            'Jumlah Terjual'
        ];
    }

    public function map($row): array
    {
        return [
            $row->tanggal,
            $row->product->nama_produk ?? '-',
            $row->jumlah_produk
        ];
    }
}
