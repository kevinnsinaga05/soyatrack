<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\UpdateStok;
use App\Models\StokBahan;
use App\Services\SalesRequirements\SalesMaterialRequirementCalculator;
use App\Services\StockService;
use Carbon\Carbon;

class UpdateStokController extends Controller
{
    public function index()
    {
        $updates = UpdateStok::selectRaw('tanggal, SUM(jumlah_produk) as total_terjual')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('updatestok', compact('updates'));
    }

    public function create()
    {
        $products = Product::orderBy('nama_produk', 'asc')->get();

        $this->ensureDefaultGulaOptions();

        // dropdown gula: fleksibel terhadap variasi penulisan kategori
        $gulas = $this->getGulaOptions();

        return view('tambahupdatestok', compact('products', 'gulas'));
    }

    /**
     * SIMPAN PENJUALAN (1 tanggal)
     */
    public function store(Request $request, StockService $stock)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'product_id' => 'required|array',
            'jumlah_produk' => 'required|array',
            'gula_id' => 'required|array',

            'product_id.*' => 'required|exists:products,id',
            'jumlah_produk.*' => 'required|integer|min:1|max:10000',
            'gula_id.*' => 'required|exists:stok_bahans,id',
        ], [
            'jumlah_produk.*.max' => 'Jumlah penjualan maksimal 10.000 cup per baris. Isi jumlah cup, bukan nominal rupiah.',
        ]);

        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');

        if (UpdateStok::whereDate('tanggal', $tanggal)->exists()) {
            return back()->withInput()->with('error', 'Tanggal tersebut sudah ada. Silakan edit dari halaman utama.');
        }

        DB::beginTransaction();
        try {
            foreach ($request->product_id as $i => $productId) {
                if (!$productId || !isset($request->jumlah_produk[$i])) continue;

                UpdateStok::create([
                    'tanggal' => $tanggal,
                    'product_id' => $productId,
                    'jumlah_produk' => $request->jumlah_produk[$i],
                    'gula_id' => $request->gula_id[$i],
                ]);
            }

            $this->applySaleStockCuts($tanggal, $stock);

            DB::commit();
            return redirect()->route('update.stok.create')->with('success', 'Update penjualan berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editByTanggal($tanggal)
    {
        $tanggal = Carbon::parse($tanggal)->format('Y-m-d');

        $products = Product::orderBy('nama_produk', 'asc')->get();

        $this->ensureDefaultGulaOptions();

        $gulas = $this->getGulaOptions();

        $updates = UpdateStok::whereDate('tanggal', $tanggal)
            ->orderBy('id')
            ->get();

        return view('editupdatestok', compact('tanggal', 'products', 'gulas', 'updates'));
    }

    private function getGulaOptions()
    {
        $rows = StokBahan::where(function ($q) {
                $q->whereRaw('LOWER(TRIM(kategori)) IN (?, ?, ?)', ['gula', 'tropicana', 'gula tropicana'])
                  ->orWhereRaw('LOWER(TRIM(nama_bahan)) LIKE ?', ['%gula%'])
                  ->orWhereRaw('LOWER(TRIM(nama_bahan)) LIKE ?', ['%tropicana%']);
            })
            ->orderBy('nama_bahan')
            ->get();

        return $rows
            ->groupBy(function ($item) {
                return strtolower(trim((string) $item->nama_bahan));
            })
            ->map(function ($group) {
                return $group
                    ->sortByDesc(function ($item) {
                        return (float) $item->total_stok;
                    })
                    ->first();
            })
            ->sortBy('nama_bahan')
            ->values();
    }

    private function ensureDefaultGulaOptions(): void
    {
        StokBahan::firstOrCreate(
            ['nama_bahan' => 'Gula Pasir'],
            [
                'kategori' => 'Gula',
                'total_stok' => 50000,
                'satuan' => 'gram',
                'stok_minimum' => 0,
                'harga_beli_per_satuan' => 0,
                'track_expired' => false,
            ]
        );

        StokBahan::firstOrCreate(
            ['nama_bahan' => 'Gula Tropicana'],
            [
                'kategori' => 'Tropicana',
                'total_stok' => 2000,
                'satuan' => 'sachet',
                'stok_minimum' => 0,
                'harga_beli_per_satuan' => 0,
                'track_expired' => false,
            ]
        );
    }

    public function showByTanggal($tanggal)
{
    $tanggal = Carbon::parse($tanggal)->format('Y-m-d');

    // ambil detail penjualan per produk
    $details = UpdateStok::with('product')
        ->whereDate('tanggal', $tanggal)
        ->orderBy('id')
        ->get();

    // hitung total terjual
    $totalTerjual = $details->sum('jumlah_produk');

    return view('lihatpenjualan', compact('tanggal', 'details', 'totalTerjual'));
}


    /**
     * UPDATE PENJUALAN (1 tanggal)
     */
    public function updateByTanggal(Request $request, $tanggal, StockService $stock)
    {
        $tanggal = Carbon::parse($tanggal)->format('Y-m-d');

        $request->validate([
            'product_id' => 'required|array',
            'jumlah_produk' => 'required|array',
            'gula_id' => 'required|array',

            'product_id.*' => 'required|exists:products,id',
            'jumlah_produk.*' => 'required|integer|min:1|max:10000',
            'gula_id.*' => 'required|exists:stok_bahans,id',
        ], [
            'jumlah_produk.*.max' => 'Jumlah penjualan maksimal 10.000 cup per baris. Isi jumlah cup, bukan nominal rupiah.',
        ]);

        DB::beginTransaction();
        try {
            $oldSales = UpdateStok::with(['product', 'gula'])
                ->whereDate('tanggal', $tanggal)
                ->orderBy('id')
                ->get();

            // hapus data lama
            UpdateStok::whereDate('tanggal', $tanggal)->delete();

            // insert ulang
            foreach ($request->product_id as $i => $productId) {
                if (!$productId || !isset($request->jumlah_produk[$i])) continue;

                UpdateStok::create([
                    'tanggal' => $tanggal,
                    'product_id' => $productId,
                    'jumlah_produk' => $request->jumlah_produk[$i],
                    'gula_id' => $request->gula_id[$i],
                ]);
            }

            $newSales = UpdateStok::with(['product', 'gula'])
                ->whereDate('tanggal', $tanggal)
                ->orderBy('id')
                ->get();

            // update stok berdasarkan selisih data lama vs data baru
            $this->applySaleStockDelta($oldSales, $newSales, $tanggal, $stock);

            DB::commit();
            return redirect()->route('update.stok.edit', $tanggal)
                ->with('success', 'Update penjualan berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Helper: hitung selisih kebutuhan bahan (new - old), lalu sesuaikan stok.
     */
    private function applySaleStockDelta($oldSales, $newSales, string $tanggal, StockService $stock): void
    {
        $oldNeeds = $this->buildMaterialNeeds($oldSales);
        $newNeeds = $this->buildMaterialNeeds($newSales);

        $bahanIds = array_unique(array_merge(array_keys($oldNeeds), array_keys($newNeeds)));

        $deltaNeeds = [];

        foreach ($bahanIds as $bahanId) {
            $oldQty = $oldNeeds[$bahanId] ?? 0;
            $newQty = $newNeeds[$bahanId] ?? 0;
            $delta = round($newQty - $oldQty, 2);

            $deltaNeeds[$bahanId] = $delta;
        }

        $positiveNeeds = [];
        foreach ($deltaNeeds as $bahanId => $delta) {
            if ($delta > 0) {
                $positiveNeeds[$bahanId] = $delta;
            }
        }

        $this->assertStockAvailability($positiveNeeds);

        foreach ($deltaNeeds as $bahanId => $delta) {

            if (abs($delta) < 0.00001) {
                continue;
            }

            if ($delta > 0) {
                $stock->reduceStock((int)$bahanId, $delta, [
                    'tanggal' => $tanggal,
                    'tipe' => 'OUT_SALE',
                    'source' => 'SALE',
                    'source_key' => $tanggal,
                    'keterangan' => 'Penyesuaian update penjualan (tambah pakai bahan)',
                ]);
                continue;
            }

            $stock->addStock((int)$bahanId, abs($delta), [
                'tanggal' => $tanggal,
                'tipe' => 'ADJUST_IN',
                'source' => 'SALE',
                'source_key' => $tanggal,
                'keterangan' => 'Penyesuaian update penjualan (kurang pakai bahan)',
            ]);
        }
    }

    /**
     * Helper: hitung total kebutuhan bahan dari kumpulan baris penjualan.
     * Return: [stok_bahan_id => qty]
     */
    private function buildMaterialNeeds($sales): array
    {
        $calculator = app(SalesMaterialRequirementCalculator::class);

        return $calculator->calculate($sales);
    }

    /**
     * Helper: cek stok semua bahan dulu agar error lebih jelas sebelum mutasi berjalan.
     */
    private function assertStockAvailability(array $needs): void
    {
        if (empty($needs)) {
            return;
        }

        $bahanMap = StokBahan::whereIn('id', array_keys($needs))
            ->get()
            ->keyBy('id');

        $errors = [];

        foreach ($needs as $bahanId => $needQty) {
            $needQty = round((float)$needQty, 2);
            if ($needQty <= 0) {
                continue;
            }

            $bahan = $bahanMap->get($bahanId);
            if (!$bahan) {
                continue;
            }

            $available = round((float)$bahan->total_stok, 2);
            if ($available + 0.00001 >= $needQty) {
                continue;
            }

            $short = round($needQty - $available, 2);
            $errors[] = sprintf(
                '- %s: butuh %.2f %s, tersedia %.2f %s, kurang %.2f %s',
                $bahan->nama_bahan,
                $needQty,
                strtoupper((string)$bahan->satuan),
                $available,
                strtoupper((string)$bahan->satuan),
                $short,
                strtoupper((string)$bahan->satuan)
            );
        }

        if (!empty($errors)) {
            throw new \RuntimeException(
                "Stok bahan tidak cukup. Silakan input stok masuk terlebih dahulu:\n" . implode("\n", $errors)
            );
        }
    }

    /**
     * DELETE PENJUALAN PER TANGGAL
     */
    public function destroyByTanggal($tanggal, StockService $stock)
    {
        $tanggal = Carbon::parse($tanggal)->format('Y-m-d');

        DB::beginTransaction();
        try {
            $stock->rollbackBySource('SALE', $tanggal);
            UpdateStok::whereDate('tanggal', $tanggal)->delete();

            DB::commit();
            return redirect()->route('update.stok')
                ->with('success', 'Data penjualan berhasil dihapus dan stok dikembalikan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('update.stok')->with('error', $e->getMessage());
        }
    }

    /**
     * HELPER: potong stok otomatis
     */
    private function applySaleStockCuts(string $tanggal, StockService $stock): void
    {
        $sales = UpdateStok::with(['product', 'gula'])->whereDate('tanggal', $tanggal)->get();

        $this->assertStockAvailability($this->buildMaterialNeeds($sales));

        // SUSU default (kategori SUSU)
        $susu = StokBahan::where('kategori', 'SUSU')
            ->orderBy('nama_bahan')
            ->first();

        foreach ($sales as $row) {
            $p = $row->product;
            $qtySold = (int)$row->jumlah_produk;

            /* ==============================
                1) SUSU
            ============================== */
            if ($susu && (float)$p->jumlah_susu > 0) {
                $need = (float)$p->jumlah_susu * $qtySold;

                $stock->reduceStock($susu->id, $need, [
                    'tanggal' => $tanggal,
                    'tipe' => 'OUT_SALE',
                    'source' => 'SALE',
                    'source_key' => $tanggal,
                    'keterangan' => "Penjualan {$p->nama_produk} (Susu)",
                ]);
            }

            /* ==============================
                2) GULA / TROPICANA (dropdown)
                FIX: pakai SATUAN untuk menentukan takaran
            ============================== */
            if ($row->gula_id && $row->gula) {
                $gulaItem = $row->gula;

                $takaran = 0;

                // ✅ Jika satuan SACHET → takaran tropicana (isi angka sachet di produk)
                if (strtoupper($gulaItem->satuan) === 'SACHET') {
                    $takaran = (float)$p->jumlah_gula_tropicana;
                } else {
                    // ✅ selain itu → gula biasa (gram)
                    $takaran = (float)$p->jumlah_gula;
                }

                if ($takaran > 0) {
                    $need = $takaran * $qtySold;

                    $stock->reduceStock($gulaItem->id, $need, [
                        'tanggal' => $tanggal,
                        'tipe' => 'OUT_SALE',
                        'source' => 'SALE',
                        'source_key' => $tanggal,
                        'keterangan' => "Penjualan {$p->nama_produk} ({$gulaItem->nama_bahan})",
                    ]);
                }
            }

            /* ==============================
                3) POWDER (pakai powder_id)
            ============================== */
            if (!empty($p->powder_id) && (float)$p->jumlah_powder > 0) {
                $powder = StokBahan::find($p->powder_id);

                if (!$powder) {
                    throw new \RuntimeException("Powder untuk produk '{$p->nama_produk}' belum ada di stok bahan.");
                }

                $need = (float)$p->jumlah_powder * $qtySold;

                $stock->reduceStock($powder->id, $need, [
                    'tanggal' => $tanggal,
                    'tipe' => 'OUT_SALE',
                    'source' => 'SALE',
                    'source_key' => $tanggal,
                    'keterangan' => "Penjualan {$p->nama_produk} (Powder {$powder->nama_bahan})",
                ]);
            }
        }
    }
}
