<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // ✅ CUP
            $table->integer('cup_regular')->default(0)->after('jumlah_powder');
            $table->integer('cup_large')->default(0)->after('cup_regular');
            $table->integer('cup_hot')->default(0)->after('cup_large');

            // ✅ BOTOL
            $table->integer('botol_250')->default(0)->after('cup_hot');
            $table->integer('botol_500')->default(0)->after('botol_250');
            $table->integer('botol_1000')->default(0)->after('botol_500');

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'cup_regular',
                'cup_large',
                'cup_hot',
                'botol_250',
                'botol_500',
                'botol_1000',
            ]);
        });
    }
};
