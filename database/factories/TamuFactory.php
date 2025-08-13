<?php

namespace Database\Factories;

use App\Models\Tamu;
use App\Models\Agenda; // <-- Jangan lupa import model Agenda
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
        // Daftar contoh instansi pemerintah di Indonesia
        $instansiPemerintah = [
            'Kementerian Keuangan',
            'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
            'Kementerian Kesehatan',
            'Kementerian Dalam Negeri',
            'Kementerian Luar Negeri',
            'Kementerian Agama',
            'Kementerian Pertahanan',
            'Kementerian Hukum dan Hak Asasi Manusia',
            'Badan Perencanaan Pembangunan Nasional (BAPPENAS)',
            'Badan Kepegawaian Negara (BKN)',
            'Badan Riset dan Inovasi Nasional (BRIN)',
            'Pemerintah Provinsi DKI Jakarta',
            'Pemerintah Provinsi Jawa Barat',
            'Pemerintah Kota Bandung',
            'Pemerintah Kabupaten Bogor',
        ];

        // Mengambil agenda_id secara acak dari tabel agendas
        // Pastikan tabel agendas sudah terisi data sebelum menjalankan seeder ini
        $agenda = Agenda::inRandomOrder()->first();

        return [
            // Membuat NIP unik yang terdiri dari 8 angka
            'NIP' => $this->faker->unique()->numerify('########'),
            
            // Menggunakan faker untuk nama (untuk nama spesifik Indonesia, atur locale di config/app.php)
            'nama_tamu' => $this->faker->name(), 
            
            // Memilih instansi secara acak dari daftar di atas
            'instansi' => $this->faker->randomElement($instansiPemerintah),
            
            // Memilih jenis kelamin secara acak
            'jk' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            
            // Menggunakan agenda_id dari data yang diambil secara acak
            'agenda_id' => $agenda->agenda_id,
        ];
    }
}
