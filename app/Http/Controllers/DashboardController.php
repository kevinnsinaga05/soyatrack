<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StokBahan;
use App\Models\UpdateStok;
use App\Models\StokBatch;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total produk
        $totalProduk = Product::count();

        // Total keseluruhan penjualan (SUM)
        $totalPenjualan = UpdateStok::sum('jumlah_produk');

        // Stok hampir habis (stok <= minimum)
        $stokHampirHabisList = StokBahan::whereColumn('total_stok', '<=', 'stok_minimum')
            ->orderBy('total_stok', 'asc')
            ->take(5)
            ->get();

        $jumlahHampirHabis = StokBahan::whereColumn('total_stok', '<=', 'stok_minimum')->count();

        $expiredSoon = StokBatch::with('stokBahan')
            ->whereNotNull('expired_at')
            ->whereDate('expired_at', '<=', Carbon::now()->addDays(7))
            ->where('qty_remaining', '>', 0)   // ✅ GANTI INI
            ->orderBy('expired_at', 'asc')
            ->get();

        return view('dashboardgressoy', compact(
        'totalProduk',
        'totalPenjualan',
        'jumlahHampirHabis',
        'stokHampirHabisList',
        'expiredSoon'
     ));
        }
}
