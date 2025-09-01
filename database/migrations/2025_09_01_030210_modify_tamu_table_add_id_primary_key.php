<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom id_tamu sebagai primary key auto increment
     * dan mengubah NIP menjadi kolom biasa agar satu pegawai bisa menghadiri banyak agenda.
     * 
     * Menggunakan pendekatan recreate table untuk SQLite compatibility.
     */
    public function up(): void
    {
        // Backup data dari tabel tamu
        $tamuData = DB::table('tamu')->get();
        
        // Drop tabel tamu yang lama
        Schema::dropIfExists('tamu');
        
        // Buat tabel tamu yang baru dengan struktur yang diinginkan
        Schema::create('tamu', function (Blueprint $table) {
            // id_tamu sebagai primary key auto increment
            $table->id('id_tamu');
            
            // NIP sebagai kolom biasa (bukan primary key)
            $table->string('NIP');
            
            // Kolom untuk data tamu
            $table->string('nama_tamu');
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            
            // Foreign key ke tabel agendas
            $table->unsignedBigInteger('agenda_id');
            $table->foreign('agenda_id')
                  ->references('agenda_id')
                  ->on('agendas')
                  ->onDelete('cascade');
            
            // Foreign key ke tabel opd untuk instansi
            $table->unsignedBigInteger('instansi')->nullable();
            $table->foreign('instansi')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
        
        // Restore data ke tabel yang baru
        foreach ($tamuData as $tamu) {
            DB::table('tamu')->insert([
                'NIP' => $tamu->NIP,
                'nama_tamu' => $tamu->nama_tamu,
                'jk' => $tamu->jk,
                'agenda_id' => $tamu->agenda_id,
                'instansi' => $tamu->instansi,
                'created_at' => $tamu->created_at,
                'updated_at' => $tamu->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Mengembalikan struktur tabel ke kondisi semula dengan NIP sebagai primary key.
     */
    public function down(): void
    {
        // Backup data dari tabel tamu yang baru
        $tamuData = DB::table('tamu')->get();
        
        // Drop tabel tamu yang baru
        Schema::dropIfExists('tamu');
        
        // Buat kembali tabel tamu dengan struktur lama (NIP sebagai primary key)
        Schema::create('tamu', function (Blueprint $table) {
            // NIP sebagai primary key
            $table->string('NIP')->primary();
            
            // Kolom untuk data tamu
            $table->string('nama_tamu');
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            
            // Foreign key ke tabel agendas
            $table->unsignedBigInteger('agenda_id');
            $table->foreign('agenda_id')
                  ->references('agenda_id')
                  ->on('agendas')
                  ->onDelete('cascade');
            
            // Foreign key ke tabel opd untuk instansi
            $table->unsignedBigInteger('instansi')->nullable();
            $table->foreign('instansi')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
        
        // Restore data ke tabel yang lama (hanya data unik berdasarkan NIP)
        $uniqueTamu = collect($tamuData)->unique('NIP');
        foreach ($uniqueTamu as $tamu) {
            DB::table('tamu')->insert([
                'NIP' => $tamu->NIP,
                'nama_tamu' => $tamu->nama_tamu,
                'jk' => $tamu->jk,
                'agenda_id' => $tamu->agenda_id,
                'instansi' => $tamu->instansi,
                'created_at' => $tamu->created_at,
                'updated_at' => $tamu->updated_at,
            ]);
        }
    }
};