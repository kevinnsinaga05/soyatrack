<?php

namespace App\Services;

use App\Models\StokBahan;
use App\Models\StokBatch;
use App\Models\StokMutation;
use App\Models\StokBatchMutation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockService
{
    public function isOutType(string $tipe): bool
    {
        return in_array($tipe, [
            'OUT_SALE',
            'ADJUST_OUT',
            'OUT_EXPIRED',
            'OPNAME_OUT',
        ], true);
    }

    public function isInType(string $tipe): bool
    {
        return in_array($tipe, [
            'IN_PURCHASE',
            'ADJUST_IN',
            'OPNAME_IN',
        ], true);
    }

    /**
     * Tambah stok (barang masuk / koreksi tambah / opname tambah)
     */
    public function addStock(int $stokBahanId, float $qty, array $meta = []): StokMutation
    {
        $tanggal = $meta['tanggal'] ?? now()->toDateString();
        $tipe = $meta['tipe'] ?? 'IN_PURCHASE';
        $source = $meta['source'] ?? null;
        $sourceKey = $meta['source_key'] ?? (string) Str::uuid();
        $expiredAt = $meta['expired_at'] ?? null;
        $hargaBeli = $meta['harga_beli_per_satuan'] ?? null;
        $note = $meta['keterangan'] ?? null;

        /** @var StokBahan $bahan */
        $bahan = StokBahan::lockForUpdate()->findOrFail($stokBahanId);

        $bahan->total_stok = (float)$bahan->total_stok + (float)$qty;
        $bahan->save();

        $batchId = null;

        // kalau bahan pakai expired, atau user isi expired, buat batch
        if (($bahan->track_expired ?? false) || $expiredAt) {
            $batch = StokBatch::create([
                'stok_bahan_id' => $bahan->id,
                'tanggal_masuk' => $tanggal,
                'expired_at' => $expiredAt,
                'qty_in' => $qty,
                'qty_remaining' => $qty,
                'harga_beli_per_satuan' => $hargaBeli,
                'note' => $note,
            ]);
            $batchId = $batch->id;
        }

        return StokMutation::create([
            'stok_bahan_id' => $bahan->id,
            'stok_batch_id' => $batchId,
            'tanggal' => $tanggal,
            'tipe' => $tipe,
            'qty' => $qty,
            'satuan' => $bahan->satuan,
            'source' => $source,
            'source_key' => $sourceKey,
            'keterangan' => $note,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Kurangi stok (penjualan / tumpah / expired / opname kurang)
     */
    public function reduceStock(int $stokBahanId, float $qty, array $meta = []): StokMutation
    {
        $tanggal = $meta['tanggal'] ?? now()->toDateString();
        $tipe = $meta['tipe'] ?? 'ADJUST_OUT';
        $source = $meta['source'] ?? null;
        $sourceKey = $meta['source_key'] ?? (string) Str::uuid();
        $note = $meta['keterangan'] ?? null;

        /** @var StokBahan $bahan */
        $bahan = StokBahan::lockForUpdate()->findOrFail($stokBahanId);

        if ((float)$bahan->total_stok < (float)$qty) {
            $available = round((float)$bahan->total_stok, 2);
            $needed = round((float)$qty, 2);
            $short = round($needed - $available, 2);

            throw new \RuntimeException(
                "Stok {$bahan->nama_bahan} tidak cukup. " .
                "Butuh {$needed} " . strtoupper((string)$bahan->satuan) .
                ", tersedia {$available} " . strtoupper((string)$bahan->satuan) .
                ", kurang {$short} " . strtoupper((string)$bahan->satuan) . "."
            );
        }

        $bahan->total_stok = (float)$bahan->total_stok - (float)$qty;
        $bahan->save();

        $mutation = StokMutation::create([
            'stok_bahan_id' => $bahan->id,
            'stok_batch_id' => null,
            'tanggal' => $tanggal,
            'tipe' => $tipe,
            'qty' => $qty,
            'satuan' => $bahan->satuan,
            'source' => $source,
            'source_key' => $sourceKey,
            'keterangan' => $note,
            'created_by' => auth()->id(),
        ]);

        // kalau track_expired aktif, kurangi batch FIFO (expired duluan)
        if ($bahan->track_expired ?? false) {
            $remaining = (float)$qty;

            $batches = StokBatch::where('stok_bahan_id', $bahan->id)
                ->where('qty_remaining', '>', 0)
                // expired yang paling dekat dipakai dulu
                ->orderByRaw('CASE WHEN expired_at IS NULL THEN 1 ELSE 0 END')
                ->orderBy('expired_at', 'asc')
                ->orderBy('tanggal_masuk', 'asc')
                ->lockForUpdate()
                ->get();

            foreach ($batches as $batch) {
                if ($remaining <= 0) break;

                $take = min($remaining, (float)$batch->qty_remaining);

                $batch->qty_remaining = (float)$batch->qty_remaining - $take;
                $batch->save();

                StokBatchMutation::create([
                    'stok_mutation_id' => $mutation->id,
                    'stok_batch_id' => $batch->id,
                    'qty' => $take,
                ]);

                $remaining -= $take;
            }

            // kalau batch tidak cukup, kasih error (biar tidak chaos)
            if ($remaining > 0.00001) {
                throw new \RuntimeException("Batch stok {$bahan->nama_bahan} tidak cukup (cek data barang masuk/expired).");
            }
        }

        return $mutation;
    }

    /**
     * Rollback mutasi per source & source_key (ini yang dipakai buat EDIT/HAPUS penjualan)
     */
    public function rollbackBySource(string $source, string $sourceKey): void
    {
        DB::transaction(function () use ($source, $sourceKey) {

            $mutations = StokMutation::where('source', $source)
                ->where('source_key', $sourceKey)
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->get();

            foreach ($mutations as $m) {
                $bahan = StokBahan::lockForUpdate()->findOrFail($m->stok_bahan_id);

                if ($this->isOutType($m->tipe)) {
                    // OUT → rollback = tambahkan stok
                    $bahan->total_stok = (float)$bahan->total_stok + (float)$m->qty;
                    $bahan->save();

                    // balikin qty_remaining batch sesuai catatan stok_batch_mutations
                    $batchRows = StokBatchMutation::where('stok_mutation_id', $m->id)->get();
                    foreach ($batchRows as $br) {
                        $batch = StokBatch::lockForUpdate()->find($br->stok_batch_id);
                        if ($batch) {
                            $batch->qty_remaining = (float)$batch->qty_remaining + (float)$br->qty;
                            $batch->save();
                        }
                        $br->delete();
                    }
                } else {
                    // IN → rollback = kurangi stok
                    $bahan->total_stok = (float)$bahan->total_stok - (float)$m->qty;
                    $bahan->save();

                    // kalau IN bikin batch, hapus batch-nya
                    if ($m->stok_batch_id) {
                        StokBatch::where('id', $m->stok_batch_id)->delete();
                    }
                }

                $m->delete();
            }
        });
    }
}
