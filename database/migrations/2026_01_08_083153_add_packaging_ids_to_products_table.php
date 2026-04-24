<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->unsignedBigInteger('cup_id_regular')->nullable()->after('cup_regular');
        $table->unsignedBigInteger('cup_id_large')->nullable()->after('cup_id_regular');
        $table->unsignedBigInteger('cup_id_hot')->nullable()->after('cup_id_large');

        $table->unsignedBigInteger('botol_id_250')->nullable()->after('botol_250');
        $table->unsignedBigInteger('botol_id_500')->nullable()->after('botol_id_250');
        $table->unsignedBigInteger('botol_id_1000')->nullable()->after('botol_id_500');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn([
            'cup_id_regular','cup_id_large','cup_id_hot',
            'botol_id_250','botol_id_500','botol_id_1000'
        ]);
    });
}

};
