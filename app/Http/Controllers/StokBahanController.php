<?php

namespace App\Http\Controllers;

use App\Models\StokBahan;
use Illuminate\Http\Request;

class StokBahanController extends Controller
{
    public function index()
    {
        $stokBahans = StokBahan::orderBy('nama_bahan')->get();
        return view('stokbahan', compact('stokBahans'));
    }

    public function create()
    {
        return view('tambahstok');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bahan'            => 'required|string|max:255',
            'kategori'              => 'required|string|max:100',
            'total_stok'            => 'required|numeric|min:0',
            'stok_minimum'          => 'required|numeric|min:0',
            'harga_beli_per_satuan' => 'nullable|numeric|min:0',
            'track_expired'         => 'boolean',
            'satuan'        => 'required|in:gram,kg,ml,liter,pcs,pack,box,sachet,bottle',
        ]);

        StokBahan::create($validated);

        return redirect()
            ->route('stok.create')
            ->with('success', 'Stok berhasil ditambahkan');
    }

    public function edit(StokBahan $stokBahan)
    {
        return view('editstok', compact('stokBahan'));
    }

    public function update(Request $request, StokBahan $stokBahan)
    {
        $validated = $request->validate([
            'nama_bahan'            => 'required|string|max:255',
            'kategori'              => 'required|string|max:100',
            'total_stok'            => 'required|numeric|min:0',
            'stok_minimum'          => 'required|numeric|min:0',
            'harga_beli_per_satuan' => 'nullable|numeric|min:0',
            'track_expired'         => 'boolean',
            'satuan'        => 'required|in:gram,kg,ml,liter,pcs,pack,box,sachet,bottle',
        ]);

        $stokBahan->update($validated);

        return redirect()
            ->route('stok.edit', $stokBahan->id)
            ->with('success', 'Stok berhasil diupdate');
    }

    public function destroy(StokBahan $stokBahan)
    {
        $stokBahan->delete();

        return redirect()
            ->route('stok.index')
            ->with('deleted', 'Stok berhasil dihapus');
    }
}
