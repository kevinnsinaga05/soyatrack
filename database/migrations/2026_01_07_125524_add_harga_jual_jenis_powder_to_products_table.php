<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // kalau kolom ini sudah ada di project kamu, hapus barisnya
            if (!Schema::hasColumn('products', 'jenis_powder')) {
                $table->string('jenis_powder')->nullable()->after('jumlah_gula');
            }

            if (!Schema::hasColumn('products', 'harga_jual')) {
                $table->decimal('harga_jual', 12, 2)->default(0)->after('nama_produk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'harga_jual')) {
                $table->dropColumn('harga_jual');
            }
            if (Schema::hasColumn('products', 'jenis_powder')) {
                $table->dropColumn('jenis_powder');
            }
        });
    }
};
