<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StokBahan;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('nama_produk')->get();
        return view('halproduk', compact('products'));
    }

    public function create()
    {
        $powders = StokBahan::whereRaw('LOWER(TRIM(kategori)) = ?', ['powder'])
            ->orderBy('nama_bahan', 'asc')
            ->get();

        return view('tambahproduk', compact('powders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',

            'jumlah_susu' => 'required|integer|min:0',
            'jumlah_gula' => 'required|integer|min:0',
            'jumlah_gula_tropicana' => 'required|integer|min:0',

            'jenis_powder' => 'required|string|max:255',
            'jumlah_powder' => 'required|integer|min:0',
        ]);

        $powderName = trim($validated['jenis_powder']);
        $powder = StokBahan::whereRaw('LOWER(TRIM(kategori)) = ?', ['powder'])
            ->whereRaw('LOWER(TRIM(nama_bahan)) = ?', [strtolower($powderName)])
            ->first();

        if (!$powder) {
            $powder = StokBahan::create([
                'nama_bahan' => $powderName,
                'kategori' => 'Powder',
                'total_stok' => 0,
                'satuan' => 'gram',
                'stok_minimum' => 0,
                'harga_beli_per_satuan' => 0,
                'track_expired' => false,
            ]);
        }

        $validated['powder_id'] = $powder->id;
        $validated['jenis_powder'] = $powder->nama_bahan;

        Product::create($validated);

        return redirect()->route('produk.create')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $powders = StokBahan::whereRaw('LOWER(TRIM(kategori)) = ?', ['powder'])
            ->orderBy('nama_bahan', 'asc')
            ->get();

        return view('editproduk', compact('product', 'powders'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',

            'jumlah_susu' => 'required|integer|min:0',
            'jumlah_gula' => 'required|integer|min:0',
            'jumlah_gula_tropicana' => 'required|integer|min:0',

            'jenis_powder' => 'required|string|max:255',
            'jumlah_powder' => 'required|integer|min:0',
        ]);

        $powderName = trim($validated['jenis_powder']);
        $powder = StokBahan::whereRaw('LOWER(TRIM(kategori)) = ?', ['powder'])
            ->whereRaw('LOWER(TRIM(nama_bahan)) = ?', [strtolower($powderName)])
            ->first();

        if (!$powder) {
            $powder = StokBahan::create([
                'nama_bahan' => $powderName,
                'kategori' => 'Powder',
                'total_stok' => 0,
                'satuan' => 'gram',
                'stok_minimum' => 0,
                'harga_beli_per_satuan' => 0,
                'track_expired' => false,
            ]);
        }

        $validated['powder_id'] = $powder->id;
        $validated['jenis_powder'] = $powder->nama_bahan;

        $product->update($validated);

        return redirect()->route('produk.edit', $product->id)->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('produk.index')->with('deleted', 'Produk berhasil dihapus');
    }
}
