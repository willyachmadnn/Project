<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar nama depan Indonesia
        $namaDepan = [
            'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gunawan', 'Hani', 'Indra', 'Joko',
            'Kartika', 'Lestari', 'Made', 'Novi', 'Oka', 'Putri', 'Qori', 'Rina', 'Sari', 'Tono',
            'Umar', 'Vina', 'Wati', 'Xenia', 'Yudi', 'Zaki', 'Agus', 'Bayu', 'Candra', 'Dian',
            'Erni', 'Fajar', 'Gita', 'Hendra', 'Ika', 'Jaka', 'Kiki', 'Lina', 'Maya', 'Nana'
        ];
        
        // Daftar nama belakang Indonesia
        $namaBelakang = [
            'Pratama', 'Sari', 'Wijaya', 'Putri', 'Santoso', 'Lestari', 'Kusuma', 'Dewi', 'Permana', 'Anggraini',
            'Setiawan', 'Maharani', 'Nugroho', 'Safitri', 'Handoko', 'Rahayu', 'Wibowo', 'Indah', 'Kurniawan', 'Sinta',
            'Gunawan', 'Melati', 'Susanto', 'Cahaya', 'Purnomo', 'Kartika', 'Hakim', 'Bunga', 'Saputra', 'Mawar'
        ];
        
        // Generate NIP unik (18 digit)
        do {
            $nip = $this->faker->numerify('##################');
        } while (DB::table('pegawai')->where('NIP', $nip)->exists());
        
        return [
            'NIP' => $nip,
            'nama_pegawai' => $this->faker->randomElement($namaDepan) . ' ' . $this->faker->randomElement($namaBelakang),
            'jk' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'instansi' => DB::table('opd')->inRandomOrder()->value('opd_id'),
        ];
    }
}
