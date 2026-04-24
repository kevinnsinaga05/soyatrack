<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_bahan_id')->constrained('stok_bahans')->cascadeOnDelete();

            $table->date('tanggal_masuk');
            $table->date('expired_at')->nullable();

            $table->decimal('qty_in', 12, 2);
            $table->decimal('qty_remaining', 12, 2);

            $table->decimal('harga_beli_per_satuan', 12, 2)->nullable();
            $table->string('note')->nullable();

            $table->timestamps();

            $table->index(['stok_bahan_id', 'expired_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_batches');
    }
};
