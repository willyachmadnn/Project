<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateAgendaOpdStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini digunakan untuk mengupdate status existing data di tabel agenda_opd
     * yang belum memiliki status (karena kolom baru ditambahkan).
     */
    public function run(): void
    {
        $this->command->info('Memperbarui status existing data agenda_opd...');
        
        // Update semua record yang statusnya NULL menjadi 'diundang'
        $updatedCount = DB::table('agenda_opd')
            ->whereNull('status')
            ->update([
                'status' => 'diundang',
                'updated_at' => now()
            ]);
            
        $this->command->info("Berhasil memperbarui {$updatedCount} record agenda_opd dengan status 'diundang'.");
        
        // Tampilkan statistik
        $totalRecords = DB::table('agenda_opd')->count();
        $diundangCount = DB::table('agenda_opd')->where('status', 'diundang')->count();
        $hadirCount = DB::table('agenda_opd')->where('status', 'hadir')->count();
        
        $this->command->info("Statistik agenda_opd:");
        $this->command->info("- Total records: {$totalRecords}");
        $this->command->info("- Status 'diundang': {$diundangCount}");
        $this->command->info("- Status 'hadir': {$hadirCount}");
    }
}