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
        Schema::table('tbl_pendistribusian', function (Blueprint $table) {
                $table->string('nik_user')->nullable();
                $table->string('nopol')->nullable();
                $table->string('departemen')->nullable();
                $table->string('jenis_kerusakan')->nullable();
                $table->integer('qty')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pendistribusian', function (Blueprint $table) {
            //
        });
    }
};
