<?php

namespace App\Services\SalesRequirements;

use App\Models\StokBahan;
use App\Models\UpdateStok;

class PowderRequirementRule extends MaterialRequirementRule
{
    /** @var array<int, StokBahan|null> */
    private array $powderCache = [];

    public function apply(UpdateStok $saleRow, array &$needs): void
    {
        $product = $saleRow->product;
        if (!$product) {
            return;
        }

        $powderId = (int) ($product->powder_id ?? 0);
        $perCup = (float) $product->jumlah_powder;

        if ($powderId <= 0 || $perCup <= 0) {
            return;
        }

        $powder = $this->getPowder($powderId);
        if (!$powder) {
            throw new \RuntimeException("Powder untuk produk '{$product->nama_produk}' belum ada di stok bahan.");
        }

        $qtySold = (int) $saleRow->jumlah_produk;
        $this->addNeed($needs, (int) $powder->id, $perCup * $qtySold);
    }

    private function getPowder(int $powderId): ?StokBahan
    {
        if (array_key_exists($powderId, $this->powderCache)) {
            return $this->powderCache[$powderId];
        }

        $this->powderCache[$powderId] = StokBahan::find($powderId);

        return $this->powderCache[$powderId];
    }
}
