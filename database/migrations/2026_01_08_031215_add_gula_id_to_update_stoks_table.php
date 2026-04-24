<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('update_stoks', function (Blueprint $table) {
            $table->unsignedBigInteger('gula_id')->nullable()->after('product_id');

            $table->foreign('gula_id')
                ->references('id')
                ->on('stok_bahans')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('update_stoks', function (Blueprint $table) {
            $table->dropForeign(['gula_id']);
            $table->dropColumn('gula_id');
        });
    }
};
