<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom dari tbl_pendistribusian
        Schema::table('tbl_pendistribusian', function (Blueprint $table) {
            $table->dropColumn(['qty', 'jenis_kerusakan', 'deskripsi']);
        });

        // Tambah kolom ke tbl_pendistribusian_items
        Schema::table('tbl_pendistribusian_items', function (Blueprint $table) {
            $table->string('jenis_kerusakan')->nullable();
        });
    }

    public function down(): void
    {
        // Tambahkan kembali kolom yang dihapus
        Schema::table('tbl_pendistribusian', function (Blueprint $table) {
            $table->integer('qty')->nullable();
            $table->string('jenis_kerusakan')->nullable();
            $table->text('deskripsi')->nullable();
        });

        // Hapus kolom yang ditambahkan
        Schema::table('tbl_pendistribusian_items', function (Blueprint $table) {
            $table->dropColumn('jenis_kerusakan');
        });
    }
};
