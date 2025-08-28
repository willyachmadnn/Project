<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil AdminSeeder terlebih dahulu
        // Urutan ini penting karena AgendaSeeder memerlukan data di tabel admins
        $this->call([
            OpdSeeder::class,
            AdminSeeder::class,   // Seeder baru untuk membuat 25 agenda per admin OPD
            TamuSeeder::class
        ]);
    }
}