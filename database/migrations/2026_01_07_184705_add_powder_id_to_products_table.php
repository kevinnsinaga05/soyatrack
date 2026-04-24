<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->unsignedBigInteger('powder_id')->nullable()->after('jumlah_gula_tropicana');

            $table->foreign('powder_id')
                ->references('id')
                ->on('stok_bahans')
                ->onDelete('set null');

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['powder_id']);
            $table->dropColumn('powder_id');
        });
    }
};
