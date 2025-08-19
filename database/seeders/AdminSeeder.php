<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array contoh untuk nama-nama orang Indonesia
        $namaAdmins = [
            'Willy',
            'Anindya',
            'Zulfahmi',
            'Nur Kholivah',
            'Admin'  // Menambahkan contoh nama
        ];

        // Array contoh untuk nama OPD (Organisasi Perangkat Daerah)
        $opdAdmins = [
            'SUB BAGIAN UMUM DAN KEPEGAWAIAN',
            'BIDANG PEREKONOMIAN, SUMBER DAYA ALAM, INFRASTRUKTUR, DAN KEWILAYAHAN',
            'BADAN PERENCANAAN PEMBANGUNAN DAERAH',
            'BAGIAN UMUM',
            'BIDANG PENYULUHAN',
            'SEKRETARIAT',
            'BIDANG PELAYANAN MEDIS',
            'DINAS KOMUNIKASI DAN INFORMATIKA'
        ];

        // Looping untuk memasukkan 100 data admin dummy
        for ($i = 1; $i <= 10; $i++) {
            $nama = $namaAdmins[array_rand($namaAdmins)];

            // PERUBAHAN: Membuat password dari nama admin
            // 1. Hapus spasi dari nama: 'Nur Kholivah' -> 'NurKholivah'
            $namaTanpaSpasi = str_replace(' ', '', $nama);
            // 2. Ubah ke huruf kecil dan tambahkan '123': 'NurKholivah' -> 'nurkholivah123'
            $password = strtolower($namaTanpaSpasi) . '123';

            DB::table('admins')->insert([
                'admin_id' => 100 + $i, // Menghasilkan ID dari 101 sampai 200
                'nama_admin' => $nama,
                'opd_admin' => $opdAdmins[array_rand($opdAdmins)],
                'password' => Hash::make($password), // Mengenkripsi password baru
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}