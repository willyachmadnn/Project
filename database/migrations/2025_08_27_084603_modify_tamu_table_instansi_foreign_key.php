<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Mengubah kolom instansi dari string menjadi foreign key ke tabel opd.
     */
    public function up(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            // Hapus kolom instansi yang lama jika ada
            if (Schema::hasColumn('tamu', 'instansi')) {
                $table->dropColumn('instansi');
            }
            
            // Tambahkan kolom instansi sebagai foreign key ke tabel opd
            $table->unsignedBigInteger('instansi')->nullable()->after('nama_tamu');
            $table->foreign('instansi')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Mengembalikan kolom instansi ke bentuk string.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            // Hapus foreign key constraint dan kolom instansi
            if (Schema::hasColumn('tamu', 'instansi')) {
                $table->dropForeign(['instansi']);
                $table->dropColumn('instansi');
            }
            
            // Kembalikan kolom instansi sebagai string
            $table->string('instansi')->after('nama_tamu');
        });
    }
};
