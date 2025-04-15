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
        Schema::table('tbl_penerimaan_items', function (Blueprint $table) {
            // Tambah kolom qty_diterima
            $table->integer('qty_diterima')->after('qty');

            // Hapus foreign key yang salah jika sudah ada
            $table->dropForeign(['penerimaan_id']);

            // Tambahkan foreign key yang benar
            $table->foreign('penerimaan_id')
                  ->references('id')
                  ->on('tbl_penerimaan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_penerimaan_items', function (Blueprint $table) {
            // Hapus foreign key
            $table->dropForeign(['penerimaan_id']);

            // Hapus kolom qty_diterima
            $table->dropColumn('qty_diterima');

            // Kembalikan foreign key yang salah (jika diperlukan)
            // $table->foreign('penerimaan_id')
            //       ->references('permintaan_id')
            //       ->on('tbl_penerimaan')
            //       ->onDelete('cascade');
        });
    }
};
