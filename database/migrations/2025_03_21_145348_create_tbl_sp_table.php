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
        Schema::create('tbl_sp', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();  // corresponds to "kode" in your array
            $table->string('jenis');           // corresponds to "jenis"
            $table->string('nama');            // corresponds to "nama"
            $table->decimal('harga', 10, 2);   // corresponds to "harga" (using decimal for monetary values)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_sp');
    }
};
