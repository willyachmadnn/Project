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
        Schema::create('tamu', function (Blueprint $table) {
            // NIP sebagai Primary Key (bukan auto-increment)
            $table->string('NIP')->primary();
            
            // Kolom untuk data tamu
            $table->string('nama_tamu');
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            
            // Foreign key ke tabel agendas
            $table->unsignedBigInteger('agenda_id');
            $table->foreign('agenda_id')
                  ->references('agenda_id') // merujuk ke primary key di tabel agendas
                  ->on('agendas') // nama tabel agenda
                  ->onDelete('cascade'); // jika agenda dihapus, tamu terkait juga akan terhapus
            
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
        Schema::dropIfExists('tamu');
    }
};
