<?php

namespace App\Http\Controllers;

use App\Models\StokBahan;
use App\Models\StokMutation;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenyesuaianStokController extends Controller
{
    public function index()
    {
        $rows = StokMutation::with('stokBahan')
            ->where('source', 'ADJUST')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('penyesuaian_index', compact('rows'));
    }

    public function create()
    {
        $bahan = StokBahan::orderBy('nama_bahan')->get();
        return view('penyesuaian_create', compact('bahan'));
    }

    public function store(Request $request, StockService $stock)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'stok_bahan_id' => 'required|exists:stok_bahans,id',
            'arah' => 'required|in:IN,OUT',
            'qty' => 'required|numeric|min:0.01',
            'alasan' => 'required|string|max:100',
        ]);

        $key  = (string) Str::uuid();
        $qty  = (float) $request->qty;
        $tipe = $request->arah === 'IN' ? 'ADJUST_IN' : 'ADJUST_OUT';

        // ✅ kalau alasan expired, pakai tipe khusus
        if (strtolower($request->alasan) === 'expired' && $request->arah === 'OUT') {
            $tipe = 'OUT_EXPIRED';
        }

        DB::beginTransaction();
        try {

            // ✅ Tambah stok
            if ($request->arah === 'IN') {
                $stock->addStock((int)$request->stok_bahan_id, $qty, [
                    'tanggal' => $request->tanggal,
                    'tipe' => $tipe,
                    'source' => 'ADJUST',
                    'source_key' => $key,
                    'keterangan' => $request->alasan,
                ]);
            }

            // ✅ Kurangi stok (qty tetap positif untuk pengurangan stok)
            else {
                $stock->reduceStock((int)$request->stok_bahan_id, $qty, [
                    'tanggal' => $request->tanggal,
                    'tipe' => $tipe,
                    'source' => 'ADJUST',
                    'source_key' => $key,
                    'keterangan' => $request->alasan,
                ]);

                // ✅ Paksa mutation qty menjadi NEGATIF agar view membaca benar
                StokMutation::where('source', 'ADJUST')
                    ->where('source_key', $key)
                    ->update([
                        'qty' => -abs($qty)
                    ]);
            }

            DB::commit();
            return redirect()->route('stok.adjust.create')->with('success', 'Penyesuaian stok berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
