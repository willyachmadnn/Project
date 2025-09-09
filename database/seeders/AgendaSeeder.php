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

        // Hapus data agenda yang sudah ada
        DB::table('agendas')->truncate();
        // Catatan: truncate() otomatis mereset auto-increment di SQLite

        $totalAgendas = 0;
        
        // Looping untuk setiap admin, buat 6 agenda per admin menggunakan AgendaFactory
        foreach ($adminIds as $adminId) {
            \App\Models\Agenda::factory(6)->create([
                'admin_id' => $adminId
            ]);
            
            $totalAgendas += 6;
        }
        
        $this->command->info("Agenda seeder berhasil dijalankan menggunakan AgendaFactory! Total agenda: " . $totalAgendas . " (6 agenda per admin)");
    }
}