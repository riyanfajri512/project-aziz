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
        Schema::create('tbl_penerimaan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penerimaan')->unique();
            $table->unsignedBigInteger('permintaan_id');
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal');
            $table->decimal('grand_total', 15, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('permintaan_id')->references('id')->on('tbl_permintaan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('tbl_penerimaan_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penerimaan_id');
            $table->string('kode_sparepart');
            $table->string('jenis_kendaraan');
            $table->string('nama_sparepart');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->integer('belance')->default(0); // ← typo "balance" diperbaiki
            $table->timestamps();

            // Relasi ke tbl_penerimaan
            $table->foreign('penerimaan_id')->references('permintaan_id')->on('tbl_penerimaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_penerimaan_items');
        Schema::dropIfExists('tbl_penerimaan');
    }
};
//