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

        $opdList = [
    // Badan (6)
    'Badan Perencanaan Pembangunan Daerah (BAPPEDA)',
    'Badan Pengelolaan Keuangan dan Aset Daerah (BPKAD)',
    'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia (BKPSDM)',
    'Badan Kesatuan Bangsa dan Politik',
    'Badan Penanggulangan Bencana Daerah (BPBD)',
    'Badan Pendapatan Daerah',
    // Dinas (16)
    'Dinas Pemberdayaan Masyarakat dan Desa',
    'Dinas Lingkungan Hidup',
    'Dinas Pengendalian Penduduk, Keluarga Berencana dan Pemberdayaan Perempuan',
    'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP)',
    'Dinas Pertanian',
    'Dinas Pendidikan',
    'Dinas Tenaga Kerja',
    'Dinas Koperasi dan Usaha Mikro',
    'Dinas Pekerjaan Umum dan Penataan Ruang (DPUPR)',
    'Dinas Perumahan Rakyat, Kawasan Permukiman dan Perhubungan (DPRKP2)',
    'Dinas Sosial',
    'Dinas Kesehatan',
    'Dinas Perindustrian dan Perdagangan',
    'Dinas Perpustakaan dan Kearsipan',
    'Dinas Pangan dan Perikanan',
    'Dinas Pemadam Kebakaran',
    // Sekretariat Daerah (4)
    'Bagian Perencanaan dan Keuangan',
    'Bagian Organisasi',
    'Bagian Umum',
    'Bagian Hukum',
    // Lainnya (4)
    'Inspektorat',
    'Dewan Perwakilan Rakyat Daerah (DPRD)',
    'Satuan Polisi Pamong Praja',
    'Rumah Sakit Umum Daerah (RSUD) Prof. Dr. Soekandar',
    // Kecamatan (12)
    'Kecamatan Bangsal',
    'Kecamatan Dlanggu',
    'Kecamatan Gedeg',
    'Kecamatan Jatirejo',
    'Kecamatan Kemlagi',
    'Kecamatan Kutorejo',
    'Kecamatan Mojoanyar',
    'Kecamatan Ngoro',
    'Kecamatan Pacet',
    'Kecamatan Puri',
    'Kecamatan Sooko',
    'Kecamatan Trowulan',
    // -----------------------------------------------------------------
    // TAMBAHAN UPT & LAINNYA (CONTOH) AGAR MENCAPAI 57 (15 ENTRI)
    // -----------------------------------------------------------------
    'UPT Puskesmas Sooko',
    'UPT Puskesmas Puri',
    'UPT Puskesmas Mojoanyar',
    'UPT Puskesmas Gedeg',
    'UPT Puskesmas Pacet',
    'UPT Puskesmas Trowulan',
    'UPT Puskesmas Ngoro',
    'UPT Pengujian Kendaraan Bermotor',
    'UPT Pengelolaan Jalan dan Jembatan Wilayah I',
    'UPT Pengelolaan Jalan dan Jembatan Wilayah II',
    'UPT Balai Latihan Kerja',
    'UPT Rumah Potong Hewan',
    'UPT Metrologi Legal',
    'Rumah Sakit Umum Daerah (RSUD) R.A. Basoeni',
    // Opsi Tambahan
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