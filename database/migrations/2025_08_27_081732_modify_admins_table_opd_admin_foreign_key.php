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
        Schema::table('admins', function (Blueprint $table) {
            // Cek apakah kolom opd_admin ada dan bertipe string
            if (Schema::hasColumn('admins', 'opd_admin')) {
                // Drop kolom opd_admin yang lama (string)
                $table->dropColumn('opd_admin');
            }
            
            // Tambah kolom opd_admin baru sebagai foreign key (nullable untuk menghindari error SQLite)
            $table->unsignedBigInteger('opd_admin')->nullable()->after('nama_admin');
            
            // Tambah foreign key constraint
            $table->foreign('opd_admin')
                  ->references('opd_id')
                  ->on('opd')
                  ->onDelete('cascade'); // Jika OPD dihapus, admin terkait juga akan terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['opd_admin']);
            
            // Drop kolom opd_admin
            $table->dropColumn('opd_admin');
            
            // Kembalikan kolom opd_admin sebagai string
            $table->string('opd_admin')->after('nama_admin');
        });
    }
};
