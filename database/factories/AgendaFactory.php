<?php

namespace Database\Factories;

use App\Models\Agenda;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agenda>
 */
class AgendaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Agenda::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Menyiapkan data dummy yang relevan dengan konteks lokal (Mojokerto)
        $jenisAcara = ['Rapat Koordinasi', 'Sosialisasi', 'Pelatihan', 'Workshop', 'Seminar', 'Musyawarah', 'Evaluasi Program'];
        $topikAcara = ['Anggaran Daerah', 'Program Kerja 2025', 'Peningkatan Kinerja ASN', 'Inovasi Pelayanan Publik', 'Pemberdayaan Masyarakat Desa', 'Digitalisasi Pemerintahan'];
        $lokasi = ['Pendopo Graha Maja Tama', 'Kantor Bupati Mojokerto', 'Hotel Ayola Sunrise', 'Aula Dinas Pendidikan', 'Ruang Rapat Bappeda', 'Kantor Kecamatan Puri'];
        $dihadiriOleh = ['Bupati Mojokerto', 'Wakil Bupati Mojokerto', 'Sekretaris Daerah', 'Asisten Pemerintahan dan Kesra', 'Kepala OPD terkait'];

        // Membuat waktu mulai dan selesai yang logis
        $jamMulai = Carbon::createFromTime($this->faker->numberBetween(8, 15), $this->faker->randomElement([0, 15, 30, 45]));
        $jamSelesai = (clone $jamMulai)->addHours($this->faker->numberBetween(1, 3));

        return [
            'nama_agenda' => $this->faker->randomElement($jenisAcara) . ' ' . $this->faker->randomElement($topikAcara),
            'tempat' => $this->faker->randomElement($lokasi),
            'tanggal' => $this->faker->dateTimeBetween('-6 months', '+6 months'),
            'jam_mulai' => $jamMulai->format('H:i:s'),
            'jam_selesai' => $jamSelesai->format('H:i:s'),
            'dihadiri' => $this->faker->randomElement($dihadiriOleh),
            // Admin ID akan diatur saat pembuatan agenda
            'admin_id' => null,
        ];
    }
}