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
        Schema::create('tbl_pendistribusian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_distribusi')->unique();
            $table->date('tanggal');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('tbl_lokasi')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pendistribusian');
    }
};
