<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('agenda', function (Blueprint $table) {
            // Menggunakan 'agenda_id' sebagai primary key, sesuai dengan model.
            $table->id('agenda_id');

            $table->string('nama_agenda');
            $table->string('tempat');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            // Menambahkan kolom 'dihadiri' yang bisa null.
            $table->string('dihadiri')->nullable();

            // Menambahkan foreign key untuk relasi ke tabel 'users'.
            // Pastikan Anda memiliki tabel 'users' dengan primary key 'id'.
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};