<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar 57 lembaga pemerintahan Indonesia yang unik
        $opdList = [
            'Kementerian Dalam Negeri',
            'Kementerian Luar Negeri',
            'Kementerian Pertahanan',
            'Kementerian Hukum dan Hak Asasi Manusia',
            'Kementerian Keuangan',
            'Kementerian Energi dan Sumber Daya Mineral',
            'Kementerian Perindustrian',
            'Kementerian Perdagangan',
            'Kementerian Pertanian',
            'Kementerian Kehutanan',
            'Kementerian Perhubungan',
            'Kementerian Pekerjaan Umum dan Perumahan Rakyat',
            'Kementerian Kesehatan',
            'Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi',
            'Kementerian Sosial',
            'Kementerian Tenaga Kerja',
            'Kementerian Lingkungan Hidup dan Kehutanan',
            'Kementerian Komunikasi dan Informatika',
            'Kementerian Agama',
            'Kementerian Pariwisata dan Ekonomi Kreatif',
            'Kementerian Investasi/BKPM',
            'Kementerian BUMN',
            'Kementerian Koordinator Politik, Hukum, dan Keamanan',
            'Kementerian Koordinator Perekonomian',
            'Kementerian Koordinator Pembangunan Manusia dan Kebudayaan',
            'Kementerian Koordinator Kemaritiman dan Investasi',
            'Sekretariat Negara',
            'Sekretariat Kabinet',
            'Sekretariat Presiden',
            'Badan Intelijen Negara',
            'Badan Siber dan Sandi Negara',
            'Lembaga Sandi Negara',
            'Badan Nasional Penanggulangan Bencana',
            'Badan Pengawasan Keuangan dan Pembangunan',
            'Badan Kepegawaian Negara',
            'Badan Pusat Statistik',
            'Arsip Nasional Republik Indonesia',
            'Perpustakaan Nasional Republik Indonesia',
            'Badan Meteorologi, Klimatologi, dan Geofisika',
            'Lembaga Ilmu Pengetahuan Indonesia',
            'Badan Riset dan Inovasi Nasional',
            'Badan Pengkajian dan Penerapan Teknologi',
            'Lembaga Penerbangan dan Antariksa Nasional',
            'Badan Tenaga Nuklir Nasional',
            'Lembaga Administrasi Negara',
            'Lembaga Ketahanan Nasional',
            'Badan Narkotika Nasional',
            'Komisi Pemberantasan Korupsi',
            'Mahkamah Agung',
            'Mahkamah Konstitusi',
            'Komisi Yudisial',
            'Kejaksaan Agung',
            'Kepolisian Negara Republik Indonesia',
            'Tentara Nasional Indonesia',
            'Dewan Perwakilan Rakyat',
            'Dewan Perwakilan Daerah',
            'Majelis Permusyawaratan Rakyat',
            'Umum'
        ];

        // Hapus data OPD yang sudah ada untuk menghindari duplikasi
        DB::table('opd')->truncate();

        // Insert data OPD dengan opd_id auto increment
        foreach ($opdList as $index => $namaOpd) {
            DB::table('opd')->insert([
                'opd_id' => $index + 1, // Auto increment dimulai dari 1
                'nama_opd' => $namaOpd,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Berhasil membuat ' . count($opdList) . ' data OPD.');
    }
}
