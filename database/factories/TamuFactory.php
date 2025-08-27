<?php

namespace Database\Factories;

use App\Models\Tamu;
use App\Models\Agenda; // <-- Jangan lupa import model Agenda
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tamu>
 */
class TamuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tamu::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Mengambil agenda_id secara acak dari tabel agendas
        // Pastikan tabel agendas sudah terisi data sebelum menjalankan seeder ini
        $agenda = Agenda::inRandomOrder()->first();
        
        // Mengambil opd_id secara acak dari tabel opd untuk kolom instansi
        // Pastikan tabel opd sudah terisi data sebelum menjalankan seeder ini
        $opd = DB::table('opd')->inRandomOrder()->first();

        return [
            // Membuat NIP unik yang terdiri dari 8 angka
            'NIP' => $this->faker->unique()->numerify('########'),
            
            // Menggunakan faker untuk nama (untuk nama spesifik Indonesia, atur locale di config/app.php)
            'nama_tamu' => $this->faker->name(), 
            
            // Menggunakan opd_id sebagai foreign key ke tabel opd
            'instansi' => $opd->opd_id,
            
            // Memilih jenis kelamin secara acak
            'jk' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            
            // Menggunakan agenda_id dari data yang diambil secara acak
            'agenda_id' => $agenda->agenda_id,
        ];
    }
}
