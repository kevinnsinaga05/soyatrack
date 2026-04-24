<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ update enum satuan: tambah sachet & bottle
        DB::statement("
            ALTER TABLE stok_bahans 
            MODIFY COLUMN satuan 
            ENUM('gram','kg','ml','liter','pcs','pack','box','sachet','bottle') 
            NOT NULL DEFAULT 'gram'
        ");
    }

    public function down(): void
    {
        // rollback: hapus sachet & bottle
        DB::statement("
            ALTER TABLE stok_bahans 
            MODIFY COLUMN satuan 
            ENUM('gram','kg','ml','liter','pcs','pack','box') 
            NOT NULL DEFAULT 'gram'
        ");
    }
};
