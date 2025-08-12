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
            'Budi Santoso',
            'Siti Rahayu',
            'Joko Susanto',
            'Ayu Lestari',
            'Dewi Indah',
            'Eko Prasetyo',
            'Nurul Hidayah',
            'Bayu Firmansyah',
            'Nur Kholivah' // Menambahkan contoh nama
        ];

        // Array contoh untuk nama OPD (Organisasi Perangkat Daerah)
        $opdAdmins = [
            'Dinas Pendidikan',
            'Dinas Kesehatan',
            'Dinas Pertanian',
            'Dinas Pariwisata',
            'Dinas Perhubungan',
            'Dinas PUPR'
        ];

        // Looping untuk memasukkan 100 data admin dummy
        for ($i = 1; $i <= 100; $i++) {
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