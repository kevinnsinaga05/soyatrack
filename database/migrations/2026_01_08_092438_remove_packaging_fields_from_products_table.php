<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $columns = [
                'jenis_cup',
                'jumlah_cup',
                'jenis_botol',
                'jumlah_botol',

                'cup_regular',
                'cup_id_regular',
                'cup_id_large',
                'cup_id_hot',
                'cup_large',
                'cup_hot',

                'botol_250',
                'botol_id_250',
                'botol_id_500',
                'botol_id_1000',
                'botol_500',
                'botol_1000',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->string('jenis_cup')->nullable();
            $table->integer('jumlah_cup')->default(0);

            $table->string('jenis_botol')->nullable();
            $table->integer('jumlah_botol')->default(0);

            $table->integer('cup_regular')->default(0);
            $table->unsignedBigInteger('cup_id_regular')->nullable();
            $table->unsignedBigInteger('cup_id_large')->nullable();
            $table->unsignedBigInteger('cup_id_hot')->nullable();

            $table->integer('cup_large')->default(0);
            $table->integer('cup_hot')->default(0);

            $table->integer('botol_250')->default(0);
            $table->unsignedBigInteger('botol_id_250')->nullable();
            $table->unsignedBigInteger('botol_id_500')->nullable();
            $table->unsignedBigInteger('botol_id_1000')->nullable();
            $table->integer('botol_500')->default(0);
            $table->integer('botol_1000')->default(0);
        });
    }
};
