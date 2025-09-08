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
     * Update status enum to include 'pegawai' option for complete status coverage.
     */
    public function up(): void
    {
        // For SQLite, we need to handle this more carefully
        try {
            // First, drop the existing index if it exists
            Schema::table('tamu', function (Blueprint $table) {
                $table->dropIndex('idx_tamu_status');
            });
        } catch (Exception $e) {
            // Index might not exist, continue
        }
        
        // Update any null status values
        DB::statement("UPDATE tamu SET status = 'umum' WHERE status IS NULL");
        
        // Drop and recreate the status column with new enum values
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('tamu', function (Blueprint $table) {
            $table->enum('status', ['pegawai', 'non-asn', 'umum'])
                  ->after('jk')
                  ->default('umum')
                  ->comment('Status tamu: pegawai, non-asn, atau umum');
            
            // Add index for better performance
            $table->index('status', 'idx_tamu_status_new');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamu', function (Blueprint $table) {
            $table->dropIndex('idx_tamu_status_new');
            $table->dropColumn('status');
        });
        
        Schema::table('tamu', function (Blueprint $table) {
            $table->enum('status', ['non-asn', 'umum'])
                  ->after('jk')
                  ->default('umum')
                  ->comment('Status tamu: non-asn (memerlukan instansi) atau umum');
            
            $table->index('status', 'idx_tamu_status');
        });
    }
};