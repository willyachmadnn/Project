<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add status column to tamu table to support non-asn and umum status types.
     * This migration aligns with the UI changes made to the non-pegawai form.
     */
    public function up(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            // Add status column after jk column
            $table->enum('status', ['non-asn', 'umum'])
                  ->after('jk')
                  ->default('umum')
                  ->comment('Status tamu: non-asn (memerlukan instansi) atau umum');
            
            // Add index for better query performance
            $table->index('status', 'idx_tamu_status');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Remove the status column from tamu table.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropIndex('idx_tamu_status');
            $table->dropColumn('status');
        });
    }
};