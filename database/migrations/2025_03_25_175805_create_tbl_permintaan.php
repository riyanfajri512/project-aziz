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
        Schema::create('tbl_permintaan', function (Blueprint $table) {
            $table->id(); // Primary Key (Auto Increment)
            $table->string('unit'); // Unit (Pembuat)
            $table->string('cabang'); // Cabang
            $table->string('lokasi'); // Lokasi
            $table->string('kode_pemesanan')->unique(); // Kode Pemesanan (Unik)
            $table->string('lokasi_id'); // Lokasi ID
            $table->date('tanggal_dibuat'); // Tanggal Dibuat
            $table->text('deskripsi')->nullable(); // Deskripsi/Catatan
            $table->string('file')->nullable(); // File (Path File)
            $table->string('sp_id'); // Sp ID
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending'); // Status
            $table->string('suplier_id'); // Suplier ID
            $table->string('sparepart_id'); // Sparepart ID
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_permintaan');
    }
};
