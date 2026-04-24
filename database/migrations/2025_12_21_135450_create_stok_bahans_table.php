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
        Schema::create('stok_bahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');

            // Jumlah stok
            $table->integer('total_stok')->default(0);

            // Batas minimum stok
            $table->integer('stok_minimum')->default(0);

            // Satuan bahan
            $table->enum('satuan', [
                'gram',
                'kg',
                'ml',
                'liter',
                'pcs',
                'pack',
                'box'
            ])->default('gram');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_bahans');
    }
};
