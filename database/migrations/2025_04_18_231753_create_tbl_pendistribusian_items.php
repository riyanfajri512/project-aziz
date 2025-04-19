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
        Schema::create('tbl_pendistribusian_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendistribusian_id')->constrained('tbl_pendistribusian')->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('tbl_sp')->onDelete('cascade');
            $table->string('kode_sparepart');
            $table->string('jenis_kendaraan');
            $table->string('nama_sparepart');
            $table->integer('stok_tersedia');
            $table->integer('qty_distribusi');
            $table->decimal('harga', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pendistribusian_items');
    }
};
