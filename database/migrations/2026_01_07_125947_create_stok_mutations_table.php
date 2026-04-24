<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_mutations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stok_bahan_id')->constrained('stok_bahans')->cascadeOnDelete();
            $table->foreignId('stok_batch_id')->nullable()->constrained('stok_batches')->nullOnDelete();

            $table->date('tanggal');
            $table->string('tipe'); // IN_PURCHASE, OUT_SALE, ADJUST_OUT, OUT_EXPIRED, OPNAME_IN, ...

            $table->decimal('qty', 12, 2);
            $table->string('satuan')->nullable(); // snapshot satuan biar aman

            $table->string('source')->nullable();     // SALE / PURCHASE / ADJUST / OPNAME
            $table->string('source_key')->nullable(); // grouping (uuid / tanggal)

            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->index(['tanggal', 'stok_bahan_id']);
            $table->index(['source', 'source_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_mutations');
    }
};
