<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua admin_id yang sudah ada di tabel 'admins'
        $adminIds = DB::table('admins')->pluck('admin_id');

        // Pastikan ada data di tabel admins sebelum seeding agenda
        if ($adminIds->isEmpty()) {
            $this->command->error("Tabel 'admins' kosong. Jalankan AdminSeeder terlebih dahulu!");
            return;
        }

        // Array contoh untuk data agenda
        $namaAgendas = ['Rapat Koordinasi', 'Musyawarah Warga', 'Sosialisasi Program', 'Pelatihan Teknis', 'Evaluasi Kinerja'];
        $tempats = ['Aula Balai Kota', 'Ruang Rapat', 'Gedung Serbaguna', 'Lapangan Desa', 'Puskesmas'];

        // Looping untuk memasukkan 100 data agenda dummy
        for ($i = 1; $i <= 10000; $i++) {
            DB::table('agendas')->insert([
                'nama_agenda' => $namaAgendas[array_rand($namaAgendas)] . ' ' . $i,
                'tempat' => $tempats[array_rand($tempats)],
                'tanggal' => now()->addDays(rand(1, 30))->toDateString(),
                'jam_mulai' => now()->addHours(rand(8, 12))->toTimeString(),
                'jam_selesai' => now()->addHours(rand(13, 17))->toTimeString(),
                'dihadiri' => Str::random(10), // Contoh string random untuk dihadiri
                'admin_id' => $adminIds->random(), // Pilih admin_id secara acak
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}