<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tamu;
use App\Models\Agenda; // <-- Import model Agenda

class TamuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Langkah 1: Periksa apakah ada data di tabel 'agendas'
        if (Agenda::count() == 0) {
            // Jika tidak ada, tampilkan pesan peringatan di konsol.
            // Seeder tidak akan berjalan untuk menghindari error.
            $this->command->warn('Tabel "agendas" masih kosong. Silakan isi data agenda terlebih dahulu sebelum menjalankan seeder tamu.');
            
            // Anda bisa juga memanggil AgendaSeeder di sini jika sudah ada
            // $this->command->info('Menjalankan AgendaSeeder...');
            // $this->call(AgendaSeeder::class);
            return;
        }

        // Langkah 2: Jika ada data agenda, buat 50 data tamu palsu
        $this->command->info('Membuat 50 data tamu...');
        Tamu::factory()->count(50)->create();
        $this->command->info('Seeder tamu berhasil dijalankan.');
    }
}
