<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat tabel pegawai dengan NIP sebagai primary key dan instansi sebagai foreign key ke tabel opd.
     */
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            // NIP sebagai Primary Key (bukan auto-increment)
            $table->string('NIP')->primary();
            
            // Kolom untuk data pegawai
            $table->string('nama_pegawai');
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            
            // Foreign key ke tabel opd
            $table->unsignedBigInteger('instansi');
            $table->foreign('instansi')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
