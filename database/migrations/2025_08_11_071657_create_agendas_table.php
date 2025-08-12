<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Mendefinisikan kelas migrasi baru yang akan membuat tabel 'agendas'.
return new class extends Migration {
    /**
     * Menjalankan migrasi.
     * Metode ini akan dieksekusi ketika migrasi dijalankan.
     */
    public function up(): void
    {
        // Membuat tabel baru di database dengan nama 'agendas'.
        Schema::create('agendas', function (Blueprint $table) {
            // Mendefinisikan kolom 'agenda_id' sebagai primary key yang auto-increment.
            $table->id('agenda_id');

            // Kolom untuk menyimpan nama atau judul agenda (tipe data string).
            $table->string('nama_agenda');

            // Kolom untuk menyimpan lokasi atau tempat agenda (tipe data string).
            $table->string('tempat');

            // Kolom untuk menyimpan tanggal pelaksanaan agenda (tipe data date).
            $table->date('tanggal');

            // Kolom untuk menyimpan waktu mulai agenda (tipe data time).
            $table->time('jam_mulai');

            // Kolom untuk menyimpan waktu selesai agenda (tipe data time).
            $table->time('jam_selesai');

            // Kolom untuk mencatat siapa saja yang menghadiri agenda (tipe data string).
            // Kolom ini boleh kosong (nullable).
            $table->string('dihadiri')->nullable();

            // Kolom untuk foreign key yang menghubungkan ke tabel 'admins'.
            // Kolom ini boleh kosong (nullable).
            $table->unsignedBigInteger('admin_id')->nullable();

            // Mendefinisikan foreign key constraint.
            $table->foreign('admin_id') // Kolom 'admin_id' di tabel 'agendas' ini
                ->references('admin_id') // merujuk ke kolom 'admin_id'
                ->on('admins') // yang ada di tabel 'admins'
                ->onDelete('set null'); // Jika admin terkait dihapus, set nilai 'admin_id' di tabel ini menjadi NULL.

            // Membuat dua kolom otomatis: 'created_at' dan 'updated_at'.
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     * Metode ini akan dieksekusi ketika migrasi dibatalkan.
     */
    public function down(): void
    {
        // Menghapus tabel 'agendas' jika tabel tersebut ada.
        Schema::dropIfExists('agendas');
    }
};