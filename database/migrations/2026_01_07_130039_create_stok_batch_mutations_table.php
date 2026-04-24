<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_batch_mutations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stok_mutation_id')->constrained('stok_mutations')->cascadeOnDelete();
            $table->foreignId('stok_batch_id')->constrained('stok_batches')->cascadeOnDelete();

            $table->decimal('qty', 12, 2);
            $table->timestamps();

            $table->index(['stok_mutation_id', 'stok_batch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_batch_mutations');
    }
};
