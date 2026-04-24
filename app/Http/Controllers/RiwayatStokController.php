<?php

namespace App\Http\Controllers;

use App\Models\StokMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatStokController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());

        $rows = StokMutation::with('stokBahan')
            ->select('stok_bahan_id',
                DB::raw("SUM(CASE WHEN tipe IN ('IN_PURCHASE','ADJUST_IN','OPNAME_IN') THEN qty ELSE 0 END) as total_masuk"),
                DB::raw("SUM(CASE WHEN tipe IN ('OUT_SALE','ADJUST_OUT','OUT_EXPIRED','OPNAME_OUT') THEN qty ELSE 0 END) as total_keluar")
            )
            ->whereDate('tanggal', $tanggal)
            ->groupBy('stok_bahan_id')
            ->orderBy('stok_bahan_id')
            ->get();

        return view('riwayatstok', compact('tanggal', 'rows'));
    }
}
