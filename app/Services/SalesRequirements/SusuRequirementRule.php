<?php

namespace App\Services\SalesRequirements;

use App\Models\StokBahan;
use App\Models\UpdateStok;

class SusuRequirementRule extends MaterialRequirementRule
{
    private ?StokBahan $susu = null;
    private bool $loaded = false;

    public function apply(UpdateStok $saleRow, array &$needs): void
    {
        $product = $saleRow->product;
        if (!$product) {
            return;
        }

        $qtySold = (int) $saleRow->jumlah_produk;
        $perCup = (float) $product->jumlah_susu;

        if ($perCup <= 0) {
            return;
        }

        $susu = $this->getSusu();
        if (!$susu) {
            return;
        }

        $this->addNeed($needs, (int) $susu->id, $perCup * $qtySold);
    }

    private function getSusu(): ?StokBahan
    {
        if ($this->loaded) {
            return $this->susu;
        }

        $this->susu = StokBahan::where('kategori', 'SUSU')
            ->orderBy('nama_bahan')
            ->first();

        $this->loaded = true;

        return $this->susu;
    }
}
