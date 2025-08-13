<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Membuat tabel 'notulens' untuk menyimpan data notulen setiap agenda.
        Schema::create('notulens', function (Blueprint $table) {
            // Primary key auto-increment
            $table->id();

            // Foreign key yang terhubung ke tabel 'agendas'.
            // Ini adalah relasi one-to-one, setiap agenda hanya punya satu notulen.
            $table->unsignedBigInteger('agenda_id')->unique(); // unique() memastikan satu agenda hanya punya satu notulen
            $table->foreign('agenda_id')
                  ->references('agenda_id') // Merujuk ke primary key di tabel 'agendas'
                  ->on('agendas')
                  ->onDelete('cascade'); // Jika agenda dihapus, notulen terkait juga akan terhapus.

            // Kolom untuk nama pembuat notulen.
            $table->string('pembuat');

            // Kolom untuk isi atau catatan dari notulen, menggunakan tipe data TEXT untuk menampung teks panjang.
            $table->text('isi_notulen');
            
            // Kolom created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notulens');
    }
};
