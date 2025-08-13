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
        $namaAgendas = ['RUPS Tahunan PT. BPR Majatama Perseroda', 'NONTON BERSAMA (INDONESIA VS VIETNAM)', 'Rapat Pleno PKK', 'Penerimaan Tim Akreditasi dan TM', 'RAPAT TINDAK LANJUT HASIL EXITMEETING TIM BPK', 'Pertemuan dan Koordinasi dalam rangka Peningkatan Produksi Tembakau TA 2025', 'Rapat Pembahasan Peraturan Daerah tentang Sempadan Jalan'];
        $tempats = ['Ruang Rapat Lt. 2 Bappeda Kab Mojokerto', 'Gedung Serba Guna Surya Kencana Dinas Pendidikan Kabupaten Mojokerto', 'Ruang Rapat BAPPEDA Lantai 2', 'Ruang Rapat Bagian Perencanaan dan Keuangan', 'Ruang Rapat Bagian Hukum Sekretariat Daerah Kabupaten Mojokerto', 'Graha Maja Tama', 'Aula Dinas Pendidikan Kabupaten Mojokerto'];
        $dihadiriOptions = [
            'Bupati Mojokerto',
            'Wakil Bupati Mojokerto',
            'Sekretaris Daerah',
            'Asisten Administrasi',
            'Kepala Bappeda',
            'Kepala Dinas Pendidikan',
            'Kepala Bagian Hukum',
            'Direktur PT BPR Majatama Perseroda',
            'Perwakilan BPK',
            'Tim Akreditasi',
            'Ketua PKK',
            'Camat',
            'Kepala Desa',
            'Kabid Perencanaan',
            'Staf/Undangan Lainnya'
        ];

        // Looping untuk memasukkan 100 data agenda dummy
        for ($i = 1; $i <= 10; $i++) {
            $dihadiri = collect($dihadiriOptions)
                ->random(rand(1, 4))
                ->implode(', ');

            DB::table('agendas')->insert([
                'nama_agenda' => $namaAgendas[array_rand($namaAgendas)] . ' ' . $i,
                'tempat' => $tempats[array_rand($tempats)],
                'tanggal' => now()->addDays(rand(1, 30))->toDateString(),
                'jam_mulai' => now()->addHours(rand(8, 12))->toTimeString(),
                'jam_selesai' => now()->addHours(rand(13, 16))->toTimeString(),
                'dihadiri' => $dihadiri, // Contoh string random untuk dihadiri
                'admin_id' => $adminIds->random(), // Pilih admin_id secara acak
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}