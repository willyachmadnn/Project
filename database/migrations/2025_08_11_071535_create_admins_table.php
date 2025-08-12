<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Mendefinisikan kelas migrasi baru yang akan membuat tabel 'admins'.
return new class extends Migration {
    /**
     * Menjalankan migrasi.
     * Metode ini akan dieksekusi ketika migrasi dijalankan (misalnya, dengan perintah `php artisan migrate`).
     */
    public function up(): void
    {
        // Membuat tabel baru di database dengan nama 'admins'.
        Schema::create('admins', function (Blueprint $table) {
            // Mendefinisikan kolom 'admin_id' sebagai primary key.
            // Tipe data unsignedBigInteger untuk konsistensi dengan foreign key.
            $table->unsignedBigInteger('admin_id')->primary();

            // Kolom untuk menyimpan nama admin (tipe data string).
            $table->string('nama_admin');

            // Kolom untuk menyimpan nama OPD (Organisasi Perangkat Daerah) dari admin (tipe data string).
            $table->string('opd_admin');

            // Kolom untuk menyimpan password admin yang sudah di-hash (tipe data string).
            $table->string('password');

            // Membuat dua kolom otomatis: 'created_at' dan 'updated_at'.
            // Berguna untuk melacak kapan record dibuat dan terakhir diubah.
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     * Metode ini akan dieksekusi ketika migrasi dibatalkan (misalnya, dengan perintah `php artisan migrate:rollback`).
     */
    public function down(): void
    {
        // Menghapus tabel 'admins' jika tabel tersebut ada di database.
        Schema::dropIfExists('admins');
    }
};