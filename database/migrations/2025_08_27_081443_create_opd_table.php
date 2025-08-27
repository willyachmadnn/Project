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
        Schema::create('opd', function (Blueprint $table) {
            // Mendefinisikan kolom 'opd_id' sebagai primary key
            $table->unsignedBigInteger('opd_id')->primary();
            
            // Kolom untuk menyimpan nama OPD (Organisasi Perangkat Daerah)
            $table->string('nama_opd');
            
            // Membuat dua kolom otomatis: 'created_at' dan 'updated_at'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd');
    }
};
