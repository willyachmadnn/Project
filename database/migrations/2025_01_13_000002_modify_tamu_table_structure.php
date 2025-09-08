<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Modify tamu table structure:
     * 1. Rename existing 'instansi' column to 'opd_id' with proper foreign key
     * 2. Add new 'instansi' column for text input (non-ASN status)
     * 3. Set opd_id to null for non-pegawai forms
     */
    public function up(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            // First, drop the existing foreign key constraint if it exists
            $table->dropForeign(['instansi']);
            
            // Rename the existing instansi column to opd_id
            $table->renameColumn('instansi', 'opd_id');
        });
        
        // In a separate schema call to avoid conflicts
        Schema::table('tamu', function (Blueprint $table) {
            // Add new instansi column for text input (for non-ASN status)
            $table->string('instansi', 255)
                  ->nullable()
                  ->after('opd_id')
                  ->comment('Instansi text field for non-ASN status');
            
            // Re-add the foreign key constraint for opd_id
            $table->foreign('opd_id')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('set null');
            
            // Add index for better performance
            $table->index('opd_id', 'idx_tamu_opd_id');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Restore the original structure.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            // Drop the new instansi column
            $table->dropColumn('instansi');
            
            // Drop foreign key and index
            $table->dropForeign(['opd_id']);
            $table->dropIndex('idx_tamu_opd_id');
            
            // Rename opd_id back to instansi
            $table->renameColumn('opd_id', 'instansi');
        });
        
        // Re-add the original foreign key
        Schema::table('tamu', function (Blueprint $table) {
            $table->foreign('instansi')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade');
        });
    }
};