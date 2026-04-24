<?php

namespace App\Services\SalesRequirements;

use App\Models\UpdateStok;

class GulaRequirementRule extends MaterialRequirementRule
{
    public function apply(UpdateStok $saleRow, array &$needs): void
    {
        $product = $saleRow->product;
        $gulaItem = $saleRow->gula;

        if (!$product || !$gulaItem) {
            return;
        }

        $qtySold = (int) $saleRow->jumlah_produk;

        $takaran = strtoupper((string) $gulaItem->satuan) === 'SACHET'
            ? (float) $product->jumlah_gula_tropicana
            : (float) $product->jumlah_gula;

        $this->addNeed($needs, (int) $gulaItem->id, $takaran * $qtySold);
    }
}
