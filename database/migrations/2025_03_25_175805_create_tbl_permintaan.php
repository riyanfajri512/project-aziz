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
            $table->id();
            $table->string('kode_pemesanan')->unique();
            $table->string('unit_pembuat');
            $table->foreignId('lokasi_id')->constrained('tbl_lokasi')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->date('tanggal_dibuat');
            $table->foreignId('supplier_id')->constrained('tbl_supplier')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->decimal('total_payment', 15, 2)->default(0);
            $table->foreignId('status_id')->constrained('tbl_status')->default(1);
            $table->text('alasan_reject')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('tbl_permintaan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained('tbl_permintaan')->onDelete('cascade');
            $table->string('kode_sparepart');
            $table->string('jenis_kendaraan');
            $table->string('nama_sparepart');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_permintaan_items');
        Schema::dropIfExists('tbl_permintaan');
    }
};
