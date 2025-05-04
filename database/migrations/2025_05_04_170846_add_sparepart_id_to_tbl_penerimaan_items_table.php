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
        Schema::table('tbl_penerimaan_items', function (Blueprint $table) {
            // Menambahkan kolom sparepart_id yang nullable
            $table->unsignedBigInteger('sparepart_id')->nullable()->after('penerimaan_id');

            // Menambahkan foreign key jika diperlukan
            $table->foreign('sparepart_id')->references('id')->on('tbl_sp')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('tbl_penerimaan_items', function (Blueprint $table) {
            $table->dropForeign(['sparepart_id']);
            $table->dropColumn('sparepart_id');
        });
    }
};
