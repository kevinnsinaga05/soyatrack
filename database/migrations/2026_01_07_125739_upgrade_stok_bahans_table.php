<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_bahans', function (Blueprint $table) {

            if (!Schema::hasColumn('stok_bahans', 'kategori')) {
                $table->string('kategori')->nullable()->after('nama_bahan');
            }

            if (!Schema::hasColumn('stok_bahans', 'harga_beli_per_satuan')) {
                $table->decimal('harga_beli_per_satuan', 12, 2)->default(0)->after('stok_minimum');
            }

            if (!Schema::hasColumn('stok_bahans', 'track_expired')) {
                $table->boolean('track_expired')->default(false)->after('harga_beli_per_satuan');
            }
        });

        // OPTIONAL tapi disarankan: bikin stok bisa desimal (butuh doctrine/dbal)
        Schema::table('stok_bahans', function (Blueprint $table) {
            // kalau kolom kamu sudah decimal, aman.
            $table->decimal('total_stok', 12, 2)->default(0)->change();
            $table->decimal('stok_minimum', 12, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('stok_bahans', function (Blueprint $table) {
            if (Schema::hasColumn('stok_bahans', 'kategori')) {
                $table->dropColumn('kategori');
            }
            if (Schema::hasColumn('stok_bahans', 'harga_beli_per_satuan')) {
                $table->dropColumn('harga_beli_per_satuan');
            }
            if (Schema::hasColumn('stok_bahans', 'track_expired')) {
                $table->dropColumn('track_expired');
            }
        });
    }
};
