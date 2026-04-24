<?php

namespace App\Http\Controllers;

use App\Models\UpdateStok;
use App\Models\StokMutation;
use App\Models\StokBatch;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->buildReportData($request);

        return view('laporan', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->buildReportData($request);

        $pdf = Pdf::loadView('laporan_pdf', $data)->setPaper('a4', 'portrait');
        $filename = 'rekap-laba-rugi-' . $data['start'] . '-sampai-' . $data['end'] . '.pdf';

        return $pdf->download($filename);
    }

    private function buildReportData(Request $request): array
    {
        $start = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end   = $request->end_date ?? now()->format('Y-m-d');

        $penjualan = UpdateStok::with('product')
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalProdukTerjual = $penjualan->sum('jumlah_produk');

        $totalOmzet = $penjualan->sum(function ($p) {
            return $p->jumlah_produk * ($p->product->harga_jual ?? 0);
        });

        $rekapHarian = $penjualan
            ->groupBy('tanggal')
            ->map(function ($rows, $tanggal) {
                $qty = $rows->sum('jumlah_produk');
                $omzet = $rows->sum(function ($r) {
                    return (int) $r->jumlah_produk * (float) ($r->product->harga_jual ?? 0);
                });

                return [
                    'tanggal' => $tanggal,
                    'qty' => $qty,
                    'omzet' => $omzet,
                ];
            })
            ->values();

        $rekapProduk = $penjualan
            ->groupBy('product_id')
            ->map(function ($rows) {
                $first = $rows->first();
                $qty = $rows->sum('jumlah_produk');
                $harga = (float) ($first->product->harga_jual ?? 0);

                return [
                    'nama_produk' => $first->product->nama_produk ?? '-',
                    'qty' => $qty,
                    'harga' => $harga,
                    'omzet' => $qty * $harga,
                ];
            })
            ->sortByDesc('omzet')
            ->values();

        $mutasiKeluar = StokMutation::where('tipe', 'OUT_SALE')
            ->whereBetween('tanggal', [$start, $end])
            ->get();

        $hpp = 0;

        foreach ($mutasiKeluar as $m) {
            $hargaAvg = StokBatch::where('stok_bahan_id', $m->stok_bahan_id)
                ->whereNotNull('harga_beli_per_satuan')
                ->avg('harga_beli_per_satuan');

            $hpp += abs($m->qty) * ($hargaAvg ?? 0);
        }

        $hpp = round($hpp);
        $labaKotor = $totalOmzet - $hpp;

        return compact(
            'start',
            'end',
            'penjualan',
            'rekapHarian',
            'rekapProduk',
            'totalProdukTerjual',
            'totalOmzet',
            'hpp',
            'labaKotor'
        );
    }
}
