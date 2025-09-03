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
     * This consolidated migration creates all necessary tables for the application
     * including all modifications and indexes in a single file.
     */
    public function up(): void
    {
        // Create OPD table
        Schema::create('opd', function (Blueprint $table) {
            $table->unsignedBigInteger('opd_id')->primary();
            $table->string('nama_opd');
            $table->timestamps();
        });

        // Create Admins table with OPD foreign key
        Schema::create('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->primary();
            $table->string('nama_admin');
            $table->unsignedBigInteger('opd_admin')->nullable();
            $table->string('password');
            $table->timestamps();
            
            $table->foreign('opd_admin')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade');
        });

        // Create Agendas table with indexes
        Schema::create('agendas', function (Blueprint $table) {
            $table->id('agenda_id');
            $table->string('nama_agenda');
            $table->string('tempat');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('dihadiri')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            
            $table->foreign('admin_id')
                  ->references('admin_id')
                  ->on('admins')
                  ->onDelete('set null');

            // Performance indexes
            $table->index('tanggal', 'idx_agendas_tanggal');
            $table->index('admin_id', 'idx_agendas_admin_id');
            $table->index('created_at', 'idx_agendas_created_at');
            $table->index(['tanggal', 'jam_mulai'], 'idx_agendas_tanggal_jam_mulai');
            $table->index(['tanggal', 'jam_selesai'], 'idx_agendas_tanggal_jam_selesai');
            $table->index('nama_agenda', 'idx_agendas_nama_agenda');
            $table->index('tempat', 'idx_agendas_tempat');
        });

        // Create Pegawai table
        Schema::create('pegawai', function (Blueprint $table) {
            $table->string('NIP')->primary();
            $table->string('nama_pegawai');
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            $table->unsignedBigInteger('instansi');
            $table->timestamps();
            
            $table->foreign('instansi')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade');
        });

        // Create Tamu table
        Schema::create('tamu', function (Blueprint $table) {
            $table->id('id_tamu');
            $table->string('NIP');
            $table->string('nama_tamu');
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            $table->unsignedBigInteger('agenda_id');
            $table->unsignedBigInteger('instansi')->nullable();
            $table->timestamps();
            
            $table->foreign('agenda_id')
                  ->references('agenda_id')
                  ->on('agendas')
                  ->onDelete('cascade');
                  
            $table->foreign('instansi')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade');
        });

        // Create Notulens table
        Schema::create('notulens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agenda_id')->unique();
            $table->string('pembuat');
            $table->text('isi_notulen');
            $table->string('pimpinan_rapat_ttd')->nullable();
            $table->timestamps();
            
            $table->foreign('agenda_id')
                  ->references('agenda_id')
                  ->on('agendas')
                  ->onDelete('cascade');
        });

        // Create Agenda OPD pivot table
        Schema::create('agenda_opd', function (Blueprint $table) {
            $table->id();
            $table->string('agenda_id');
            $table->unsignedBigInteger('opd_id');
            $table->timestamps();
            
            $table->foreign('agenda_id')
                  ->references('agenda_id')
                  ->on('agendas')
                  ->onDelete('cascade');
                  
            $table->foreign('opd_id')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade');
                  
            $table->unique(['agenda_id', 'opd_id']);
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drop all tables in the correct order to respect foreign key constraints.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_opd');
        Schema::dropIfExists('notulens');
        Schema::dropIfExists('tamu');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('opd');
    }
};