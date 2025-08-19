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
        Schema::table('agendas', function (Blueprint $table) {
            // Index untuk kolom yang sering diquery
            $table->index('tanggal', 'idx_agendas_tanggal');
            $table->index('admin_id', 'idx_agendas_admin_id');
            $table->index('created_at', 'idx_agendas_created_at');
            $table->index(['tanggal', 'jam_mulai'], 'idx_agendas_tanggal_jam_mulai');
            $table->index(['tanggal', 'jam_selesai'], 'idx_agendas_tanggal_jam_selesai');
            
            // Index untuk kolom pencarian (SQLite tidak mendukung fulltext)
            $table->index('nama_agenda', 'idx_agendas_nama_agenda');
            $table->index('tempat', 'idx_agendas_tempat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex('idx_agendas_tanggal');
            $table->dropIndex('idx_agendas_admin_id');
            $table->dropIndex('idx_agendas_created_at');
            $table->dropIndex('idx_agendas_tanggal_jam_mulai');
            $table->dropIndex('idx_agendas_tanggal_jam_selesai');
            $table->dropIndex('idx_agendas_nama_agenda');
            $table->dropIndex('idx_agendas_tempat');
        });
    }
};
