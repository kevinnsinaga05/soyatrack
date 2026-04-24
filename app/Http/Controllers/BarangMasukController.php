<?php

namespace App\Http\Controllers;

use App\Models\StokBahan;
use App\Models\StokMutation;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BarangMasukController extends Controller
{
    public function index()
{
    $rows = \App\Models\StokBatch::with('stokBahan')
        ->orderBy('tanggal_masuk', 'desc')
        ->orderBy('id', 'desc')
        ->paginate(20);

    return view('stokmasuk_index', compact('rows'));
}

    public function create()
    {
        $bahan = StokBahan::orderBy('nama_bahan')->get();
        return view('stokmasuk_create', compact('bahan'));
    }

    public function store(Request $request, StockService $stock)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'stok_bahan_id' => 'required|array',
            'qty' => 'required|array',
            'stok_bahan_id.*' => 'required|exists:stok_bahans,id',
            'qty.*' => 'required|numeric|min:0.01',
            'expired_at' => 'nullable|array',
            'expired_at.*' => 'nullable|date',
            'harga_beli_per_satuan' => 'nullable|array',
            'harga_beli_per_satuan.*' => 'nullable|numeric|min:0',
        ]);

        $key = (string) Str::uuid();

        DB::beginTransaction();
        try {
            foreach ($request->stok_bahan_id as $i => $id) {
                $qty = (float) ($request->qty[$i] ?? 0);
                if ($qty <= 0) continue;

                $stock->addStock((int)$id, $qty, [
                    'tanggal' => $request->tanggal,
                    'tipe' => 'IN_PURCHASE',
                    'source' => 'PURCHASE',
                    'source_key' => $key,
                    'expired_at' => $request->expired_at[$i] ?? null,
                    'harga_beli_per_satuan' => $request->harga_beli_per_satuan[$i] ?? null,
                    'keterangan' => 'Barang masuk',
                ]);
            }

            DB::commit();
            return redirect()->route('stok.masuk.create')->with('success', 'Barang masuk berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    public function edit($id)
{
    $row = StokMutation::with('stokBahan')->findOrFail($id);
    $bahan = StokBahan::orderBy('nama_bahan')->get();

    return view('stokmasuk_edit', compact('row', 'bahan'));
}

public function update(Request $request, $id, StockService $stock)
{
    $row = StokMutation::findOrFail($id);

    $request->validate([
        'tanggal' => 'required|date',
        'stok_bahan_id' => 'required|exists:stok_bahans,id',
        'qty' => 'required|numeric|min:0.01',
        'expired_at' => 'nullable|date',
        'harga_beli_per_satuan' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        // ✅ rollback stok lama
        $stock->rollbackBySource('PURCHASE', $row->source_key);

        // ✅ hapus semua mutation lama yang memiliki source_key sama
        StokMutation::where('source', 'PURCHASE')
            ->where('source_key', $row->source_key)
            ->delete();

        // ✅ insert ulang dengan data baru (pakai source_key lama)
        $stock->addStock((int)$request->stok_bahan_id, (float)$request->qty, [
            'tanggal' => $request->tanggal,
            'tipe' => 'IN_PURCHASE',
            'source' => 'PURCHASE',
            'source_key' => $row->source_key,
            'expired_at' => $request->expired_at,
            'harga_beli_per_satuan' => $request->harga_beli_per_satuan,
            'keterangan' => 'Barang masuk (edit)',
        ]);

        DB::commit();
        return redirect()->route('stok.masuk.index')->with('success', 'Data stok masuk berhasil diperbarui.');
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withInput()->with('error', $e->getMessage());
    }
}

public function destroy($id, StockService $stock)
{
    $batch = \App\Models\StokBatch::findOrFail($id);

    DB::beginTransaction();
    try {

        // rollback jumlah sisa batch
        $qtyRollback = (float) $batch->qty_remaining;

        if ($qtyRollback > 0) {
            $stock->reduceStock($batch->stok_bahan_id, $qtyRollback, [
                'tanggal' => now()->format('Y-m-d'),
                'tipe' => 'OUT_ADJUST',
                'source' => 'DELETE_BATCH',
                'source_key' => (string) \Illuminate\Support\Str::uuid(),
                'keterangan' => "Rollback hapus batch stok masuk ID {$batch->id}",
            ]);
        }

        $batch->delete();

        DB::commit();
        return redirect()->route('stok.masuk.index')
            ->with('success', 'Batch stok masuk berhasil dihapus dan stok dikembalikan.');
    } catch (\Throwable $e) {
        DB::rollBack();
        return redirect()->route('stok.masuk.index')
            ->with('error', $e->getMessage());
    }
}



}
