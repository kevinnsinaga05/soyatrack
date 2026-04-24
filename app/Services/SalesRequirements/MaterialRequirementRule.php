<?php

namespace App\Services\SalesRequirements;

use App\Models\UpdateStok;

abstract class MaterialRequirementRule
{
    /**
     * Apply rule ke satu baris penjualan dan akumulasi kebutuhan bahan.
     */
    abstract public function apply(UpdateStok $saleRow, array &$needs): void;

    protected function addNeed(array &$needs, int $stokBahanId, float $qty): void
    {
        if ($qty <= 0) {
            return;
        }

        $needs[$stokBahanId] = ($needs[$stokBahanId] ?? 0) + $qty;
    }
}
