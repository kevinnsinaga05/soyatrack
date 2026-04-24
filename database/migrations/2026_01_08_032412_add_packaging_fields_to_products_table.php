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
    Schema::table('products', function (Blueprint $table) {
        $table->enum('jenis_cup', ['REGULAR','LARGE','HOT'])->nullable()->after('jumlah_powder');
        $table->integer('jumlah_cup')->default(1)->after('jenis_cup');

        $table->enum('jenis_botol', ['250ML','500ML','1L'])->nullable()->after('jumlah_cup');
        $table->integer('jumlah_botol')->default(0)->after('jenis_botol');
    });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['jenis_cup','jumlah_cup','jenis_botol','jumlah_botol']);
        });
    }
};
