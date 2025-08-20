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

        // Looping untuk memasukkan 1000 data agenda dummy dengan status bervariasi
        for ($i = 1; $i <= 1000; $i++) {
            $dihadiri = collect($dihadiriOptions)
                ->random(rand(1, 4))
                ->implode(', ');

            // Buat status acak: 40% Selesai, 30% Menunggu, 30% Berlangsung
            $statusRandom = rand(1, 100);
            
            if ($statusRandom <= 40) {
                // Status 'Selesai' - agenda di masa lalu
                $tanggal = now()->subDays(rand(1, 90))->toDateString();
                $jamMulai = sprintf('%02d:%02d', rand(8, 14), rand(0, 59));
                $jamSelesai = sprintf('%02d:%02d', rand(15, 18), rand(0, 59));
            } elseif ($statusRandom <= 70) {
                // Status 'Menunggu' - agenda di masa depan
                $tanggal = now()->addDays(rand(1, 60))->toDateString();
                $jamMulai = sprintf('%02d:%02d', rand(8, 14), rand(0, 59));
                $jamSelesai = sprintf('%02d:%02d', rand(15, 18), rand(0, 59));
            } else {
                // Status 'Berlangsung' - agenda hari ini dengan waktu sedang berlangsung
                $tanggal = now()->toDateString();
                $currentHour = now()->hour;
                // Pastikan agenda sedang berlangsung (mulai sebelum sekarang, selesai setelah sekarang)
                $jamMulai = sprintf('%02d:%02d', max(8, $currentHour - rand(1, 3)), rand(0, 59));
                $jamSelesai = sprintf('%02d:%02d', min(18, $currentHour + rand(1, 4)), rand(0, 59));
            }

            DB::table('agendas')->insert([
                'nama_agenda' => $namaAgendas[array_rand($namaAgendas)] . ' ' . $i,
                'tempat' => $tempats[array_rand($tempats)],
                'tanggal' => $tanggal,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'dihadiri' => $dihadiri,
                'admin_id' => $adminIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}