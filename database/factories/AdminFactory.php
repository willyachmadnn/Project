<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar kota-kota di Indonesia untuk opd_admin
        $bidangopd = [
"Majelis Permusyawaratan Rakyat (MPR)",
"Dewan Perwakilan Rakyat (DPR)",
"Dewan Perwakilan Daerah (DPD)",
"Presiden dan Wakil Presiden",
"Mahkamah Agung (MA)",
"Mahkamah Konstitusi (MK)",
"Komisi Yudisial (KY)",
"Badan Pemeriksa Keuangan (BPK)",
"Kementerian Koordinator Bidang Politik, Hukum, dan Keamanan",
"Kementerian Koordinator Bidang Perekonomian",
"Kementerian Koordinator Bidang Pembangunan Manusia dan Kebudayaan",
"Kementerian Koordinator Bidang Kemaritiman dan Investasi",
"Kementerian Sekretariat Negara",
"Kementerian Dalam Negeri",
"Kementerian Luar Negeri",
"Kementerian Pertahanan",
"Kementerian Agama",
"Kementerian Hukum dan Hak Asasi Manusia",
"Kementerian Keuangan",
"Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi",
"Kementerian Kesehatan",
"Kementerian Sosial",
"Kementerian Ketenagakerjaan",
"Kementerian Perindustrian",
"Kementerian Perdagangan",
"Kementerian Energi dan Sumber Daya Mineral",
"Kementerian Pekerjaan Umum dan Perumahan Rakyat",
"Kementerian Perhubungan",
"Kementerian Komunikasi dan Informatika",
"Kementerian Pertanian",
"Kementerian Lingkungan Hidup dan Kehutanan",
"Kementerian Kelautan dan Perikanan",
"Kementerian Desa, Pembangunan Daerah Tertinggal, dan Transmigrasi",
"Kementerian Agraria dan Tata Ruang/Badan Pertanahan Nasional",
"Kementerian Perencanaan Pembangunan Nasional/Badan Perencanaan Pembangunan Nasional (Bappenas)",
"Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi",
"Kementerian Badan Usaha Milik Negara (BUMN)",
"Kementerian Koperasi dan Usaha Kecil dan Menengah",
"Kementerian Pariwisata dan Ekonomi Kreatif/Badan Pariwisata dan Ekonomi Kreatif",
"Kementerian Pemberdayaan Perempuan dan Perlindungan Anak",
"Kementerian Investasi/Badan Koordinasi Penanaman Modal (BKPM)",
"Kementerian Pemuda dan Olahraga",
"Kementerian Sains dan Teknologi",
"Kementerian Pembangunan Ibu Kota Negara",
"Kementerian Kependudukan dan Pembangunan Keluarga/Badan Kependudukan dan Keluarga Berencana Nasional (BKKBN)",
"Kementerian Imigrasi dan Pemasyarakatan",
"Tentara Nasional Indonesia (TNI)",
"Kepolisian Negara Republik Indonesia (Polri)",
"Kejaksaan Agung",
"Bank Indonesia (BI)",
"Badan Intelijen Negara (BIN)",
"Badan Narkotika Nasional (BNN)",
"Badan Nasional Penanggulangan Bencana (BNPB)",
"Badan Siber dan Sandi Negara (BSSN)",
"Komisi Pemberantasan Korupsi (KPK)",
"Otoritas Jasa Keuangan (OJK)",
"Lembaga Penjamin Simpanan (LPS)"
];

        // Menghasilkan admin_id berupa angka ratusan (1000-9999)
        // Mulai dari 1000 untuk menghindari konflik dengan admin_id yang sudah ada
        $adminId = $this->faker->unique()->numberBetween(100, 999);

        // Menghasilkan nama orang Indonesia tanpa gelar
        $namaDepan = [
            'Adi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gunawan', 'Hesti', 'Irfan', 'Joko', 
            'Kartika', 'Lina', 'Muhamad', 'Nina', 'Oki', 'Putri', 'Rudi', 'Siti', 'Tono', 'Umi', 
            'Vina', 'Wawan', 'Yanti', 'Zainal', 'Agus', 'Bambang', 'Dian', 'Endang', 'Faisal', 'Gita', 
            'Hendra', 'Indah', 'Jaya', 'Kurnia', 'Laras', 'Maman', 'Nita', 'Pandu', 'Ratna', 'Surya', 
            'Tuti', 'Udin', 'Vera', 'Wati', 'Yuda', 'Zulfa', 'Anwar', 'Bayu', 'Cahya', 'Dodi'
        ];
        
        $namaBelakang = [
            'Wijaya', 'Kusuma', 'Hidayat', 'Nugraha', 'Saputra', 'Santoso', 'Wibowo', 'Susanto', 'Setiawan', 'Pratama', 
            'Putra', 'Sari', 'Utami', 'Lestari', 'Suryadi', 'Hartono', 'Permadi', 'Irawan', 'Gunawan', 'Ramadhan', 
            'Firmansyah', 'Budiman', 'Hakim', 'Wahyudi', 'Sulistyo', 'Purnama', 'Handoko', 'Sugiarto', 'Mulya', 'Prabowo', 
            'Suryanto', 'Wicaksono', 'Nugroho', 'Hermawan', 'Kurniawan', 'Pranoto', 'Widodo', 'Sudrajat', 'Maulana', 'Ardiansyah'
        ];
        
        // Pilih nama depan dan belakang secara acak
        $namaAdmin = $this->faker->randomElement($namaDepan) . ' ' . $this->faker->randomElement($namaBelakang);

        // Menghasilkan opd_admin dengan format "Admin <nama kota di Indonesia>"
        $opdrandom = $this->faker->randomElement($bidangopd);
        $opdAdmin = "Admin {$opdrandom}";

        return [
            'admin_id' => $adminId,
            'nama_admin' => $namaAdmin,
            'opd_admin' => $opdAdmin,
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}