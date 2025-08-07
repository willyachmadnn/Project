<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat user admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Membuat beberapa agenda contoh
        Agenda::create([
            'nama_agenda' => 'Rapat Koordinasi Pembangunan',
            'tempat' => 'Aula Kantor Bupati',
            'tanggal' => '2025-08-15',
            'jam_mulai' => '09:00',
            'jam_selesai' => '12:00',
        ]);

        Agenda::create([
            'nama_agenda' => 'Musyawarah Perencanaan Pembangunan',
            'tempat' => 'Gedung Serbaguna',
            'tanggal' => '2025-08-20',
            'jam_mulai' => '10:00',
            'jam_selesai' => '15:00',
        ]);

        Agenda::create([
            'nama_agenda' => 'Sosialisasi Program Kesehatan',
            'tempat' => 'Puskesmas Kecamatan',
            'tanggal' => '2025-08-25',
            'jam_mulai' => '13:00',
            'jam_selesai' => '16:00',
        ]);
    }
}
