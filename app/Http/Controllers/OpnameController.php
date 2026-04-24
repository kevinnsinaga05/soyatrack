<?php

namespace App\Http\Controllers;

use App\Models\StokBahan;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpnameController extends Controller
{
    public function create()
    {
        $bahan = StokBahan::orderBy('nama_bahan')->get();
        return view('opname_create', compact('bahan'));
    }

    public function store(Request $request, StockService $stock)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'actual' => 'required|array',
            'actual.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->actual as $stokBahanId => $actualValue) {
                if ($actualValue === null || $actualValue === '') continue;

                $bahan = StokBahan::lockForUpdate()->findOrFail($stokBahanId);
                $current = (float)$bahan->total_stok;
                $actual = (float)$actualValue;

                $delta = $actual - $current;
                if (abs($delta) < 0.00001) continue;

                if ($delta > 0) {
                    $stock->addStock((int)$bahan->id, (float)$delta, [
                        'tanggal' => $request->tanggal,
                        'tipe' => 'OPNAME_IN',
                        'source' => 'OPNAME',
                        'source_key' => $request->tanggal,
                        'keterangan' => 'Opname/Kalibrasi',
                    ]);
                } else {
                    $stock->reduceStock((int)$bahan->id, (float)abs($delta), [
                        'tanggal' => $request->tanggal,
                        'tipe' => 'OPNAME_OUT',
                        'source' => 'OPNAME',
                        'source_key' => $request->tanggal,
                        'keterangan' => 'Opname/Kalibrasi',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('stok.opname.create')->with('success', 'Opname berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
