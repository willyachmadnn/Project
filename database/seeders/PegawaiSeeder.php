<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use App\Models\Opd;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel OPD sudah memiliki data
        if (Opd::count() === 0) {
            $this->command->info('Tabel OPD kosong. Jalankan OpdSeeder terlebih dahulu.');
            return;
        }
        
        // Hapus data pegawai yang sudah ada (jika ada)
        Pegawai::truncate();
        
        // Buat 20 data pegawai menggunakan factory
        Pegawai::factory()->count(20)->create();
        
        $this->command->info('20 data pegawai berhasil dibuat.');
    }
}
