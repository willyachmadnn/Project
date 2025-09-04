<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use App\Models\Opd;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel OPD sudah memiliki data
        if (Opd::count() === 0) {
            $this->command->info('Tabel OPD kosong. Jalankan OpdSeeder terlebih dahulu.');
            return;
        }

        // Hapus data pegawai yang sudah ada (jika ada)
        Pegawai::truncate();

        $this->command->info('Membuat data pegawai...');

        // Data pegawai realistis untuk testing
        $pegawaiData = [
            [
                'NIP' => '196801011990031001',
                'nama_pegawai' => 'Dr. Ahmad Wijaya, S.Kom, M.T',
                'jk' => 'Laki-laki',
                'instansi' => 1, // Sesuaikan dengan OPD ID yang ada
            ],
            [
                'NIP' => '102030405060708090',
                'nama_pegawai' => 'Anindya Admawati',
                'jk' => 'Perempuan',
                'instansi' => 19, // Sesuaikan dengan OPD ID yang ada
            ],
            [
                'NIP' => '197205152000032002',
                'nama_pegawai' => 'Siti Nurhaliza, S.E, M.M',
                'jk' => 'Perempuan',
                'instansi' => 2,
            ],
            [
                'NIP' => '198003101995121003',
                'nama_pegawai' => 'Budi Santoso, S.T',
                'jk' => 'Laki-laki',
                'instansi' => 3,
            ],
            [
                'NIP' => '203040506070809010',
                'nama_pegawai' => 'Willy Achmad Nurani',
                'jk' => 'Laki-laki',
                'instansi' => 18,
            ],
            [
                'NIP' => '198512252010012004',
                'nama_pegawai' => 'Dewi Kartika, S.Pd, M.Pd',
                'jk' => 'Perempuan',
                'instansi' => 4,
            ],
            [
                'NIP' => '199001081015031005',
                'nama_pegawai' => 'Eko Prasetyo, S.H',
                'jk' => 'Laki-laki',
                'instansi' => 5,
            ],
            [
                'NIP' => '198707192012032006',
                'nama_pegawai' => 'Fitri Handayani, S.Sos',
                'jk' => 'Perempuan',
                'instansi' => 6,
            ],
            [
                'NIP' => '199203151018031007',
                'nama_pegawai' => 'Gunawan Setiawan, S.Kom',
                'jk' => 'Laki-laki',
                'instansi' => 7,
            ],
            [
                'NIP' => '20252025100200100200',
                'nama_pegawai' => 'M. Zulfahmi Aulawi',
                'jk' => 'Laki-laki',
                'instansi' => 17,
            ],
            [
                'NIP' => '198909282014032008',
                'nama_pegawai' => 'Hani Permatasari, S.E',
                'jk' => 'Perempuan',
                'instansi' => 8,
            ],
            [
                'NIP' => '199505101020031009',
                'nama_pegawai' => 'Indra Kusuma, S.T, M.T',
                'jk' => 'Laki-laki',
                'instansi' => 9,
            ],
            [
                'NIP' => '199112052016032010',
                'nama_pegawai' => 'Joko Widodo, S.Pd',
                'jk' => 'Laki-laki',
                'instansi' => 10,
            ],
            [
                'NIP' => '198804201013032011',
                'nama_pegawai' => 'Kartika Sari, S.Psi',
                'jk' => 'Perempuan',
                'instansi' => 11,
            ],
            [
                'NIP' => '20252025100100100100',
                'nama_pegawai' => 'Nur Kholivah',
                'jk' => 'Perempuan',
                'instansi' => 16,
            ],
            [
                'NIP' => '199408151019031012',
                'nama_pegawai' => 'Lestari Wulandari, S.Kom',
                'jk' => 'Perempuan',
                'instansi' => 12,
            ],
            [
                'NIP' => '199702201022031013',
                'nama_pegawai' => 'Made Sutrisno, S.E',
                'jk' => 'Laki-laki',
                'instansi' => 13,
            ],
            [
                'NIP' => '199006101015032014',
                'nama_pegawai' => 'Novi Rahmawati, S.Pd',
                'jk' => 'Perempuan',
                'instansi' => 14,
            ],
            [
                'NIP' => '199801251023031015',
                'nama_pegawai' => 'Oka Mahendra, S.H, M.H',
                'jk' => 'Laki-laki',
                'instansi' => 15,
            ]
        ];

        // Insert data pegawai
        foreach ($pegawaiData as $pegawai) {
            // Pastikan instansi ID ada di tabel OPD
            $opdExists = DB::table('opd')->where('opd_id', $pegawai['instansi'])->exists();
            if (!$opdExists) {
                // Jika OPD ID tidak ada, gunakan OPD pertama yang tersedia
                $pegawai['instansi'] = DB::table('opd')->first()->opd_id;
            }

            Pegawai::create($pegawai);
        }

        // Tambahkan beberapa data random untuk variasi
        $randomCount = 10;
        Pegawai::factory()->count($randomCount)->create();

        $totalPegawai = count($pegawaiData) + $randomCount;
        $this->command->info("{$totalPegawai} data pegawai berhasil dibuat.");
    }
}