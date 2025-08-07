<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agenda;
use App\Models\User;

class AgendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Langkah 1: Pastikan ada setidaknya satu user di database sebagai admin.
        // Jika belum ada, seeder ini akan membuat satu user dummy.
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Admin Pemkab',
                'email' => 'admin@mojokertokab.go.id',
            ]);
        }

        // Langkah 2: Panggil AgendaFactory untuk membuat 100 data dummy.
        // Pesan ini akan muncul di terminal saat seeder berjalan.
        $this->command->info('Membuat 100 data agenda dummy...');

        Agenda::factory()->count(100)->create();

        $this->command->info('Seeding data agenda berhasil!');
    }
}