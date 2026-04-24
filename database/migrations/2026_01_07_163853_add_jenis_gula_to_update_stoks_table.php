<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('update_stoks', function (Blueprint $table) {
            $table->enum('jenis_gula', ['BIASA', 'TROPICANA'])
                ->default('BIASA')
                ->after('jumlah_produk');
        });
    }

    public function down(): void
    {
        Schema::table('update_stoks', function (Blueprint $table) {
            $table->dropColumn('jenis_gula');
        });
    }
};
