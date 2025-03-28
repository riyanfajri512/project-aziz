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
        Schema::create('pendistribusian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_distribusi')->unique(); // Kode unik untuk distribusi
            $table->unsignedBigInteger('penerimaan_id');
            $table->integer('jumlah'); // Jumlah yang dikurangi dari balance
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sparepart_dis_id');
            $table->timestamps();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pendistribusian');
    }
};
