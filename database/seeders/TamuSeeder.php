<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tamu;
use App\Models\Agenda;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class TamuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel agendas sudah memiliki data
        if (Agenda::count() === 0) {
            $this->command->warn('Tabel agendas kosong. Jalankan AgendaSeeder terlebih dahulu untuk mendapatkan data yang lebih realistis.');
            return;
        }
        
        // Hapus data tamu yang sudah ada
        Tamu::truncate();
        
        $this->command->info('Membuat data tamu...');
        
        // Ambil beberapa agenda untuk testing
        $agendas = Agenda::limit(5)->get();
        $pegawais = Pegawai::limit(10)->get();
        
        if ($agendas->isEmpty()) {
            $this->command->warn('Tidak ada agenda tersedia.');
            return;
        }
        
        // Counter untuk auto increment tamu non-pegawai
        $tamuCounter = 1;
        
        // Data tamu pegawai (menggunakan NIP pegawai yang sudah ada)
        // Setiap pegawai hanya untuk satu agenda untuk menghindari duplikasi NIP
        if ($pegawais->isNotEmpty()) {
            foreach ($pegawais->take(10) as $index => $pegawai) {
                $agenda = $agendas->get($index % $agendas->count());
                Tamu::create([
                    'NIP' => $pegawai->NIP,
                    'nama_tamu' => $pegawai->nama_pegawai,
                    'jk' => $pegawai->jk,
                    'instansi' => $pegawai->instansi,
                    'agenda_id' => $agenda->agenda_id,
                ]);
            }
        }
        
        // Data tamu non-pegawai dengan format NIP #tamu<auto_increment>
        $nonPegawaiData = [
            ['nama' => 'Andi Pratama', 'jk' => 'Laki-laki'],
            ['nama' => 'Sari Indah', 'jk' => 'Perempuan'],
            ['nama' => 'Budi Hartono', 'jk' => 'Laki-laki'],
            ['nama' => 'Citra Dewi', 'jk' => 'Perempuan'],
            ['nama' => 'Dedi Kurniawan', 'jk' => 'Laki-laki'],
            ['nama' => 'Eka Putri', 'jk' => 'Perempuan'],
            ['nama' => 'Fajar Nugroho', 'jk' => 'Laki-laki'],
            ['nama' => 'Gita Sari', 'jk' => 'Perempuan'],
            ['nama' => 'Hendra Wijaya', 'jk' => 'Laki-laki'],
            ['nama' => 'Ira Wati', 'jk' => 'Perempuan'],
            ['nama' => 'Joko Susilo', 'jk' => 'Laki-laki'],
            ['nama' => 'Kiki Amelia', 'jk' => 'Perempuan'],
            ['nama' => 'Lukman Hakim', 'jk' => 'Laki-laki'],
            ['nama' => 'Maya Sari', 'jk' => 'Perempuan'],
            ['nama' => 'Nanda Pratama', 'jk' => 'Laki-laki'],
        ];
        
        foreach ($nonPegawaiData as $tamu) {
            foreach ($agendas->take(2) as $agenda) {
                Tamu::create([
                    'NIP' => '#tamu' . str_pad($tamuCounter, 3, '0', STR_PAD_LEFT),
                    'nama_tamu' => $tamu['nama'],
                    'jk' => $tamu['jk'],
                    'instansi' => 43, // ID instansi untuk tamu non-pegawai (Umum)
                    'agenda_id' => $agenda->agenda_id,
                ]);
                $tamuCounter++;
            }
        }
        
        // Tambahkan beberapa data random untuk variasi
        $randomCount = 20;
        for ($i = 0; $i < $randomCount; $i++) {
            $randomAgenda = $agendas->random();
            $randomNames = [
                'Ahmad Fauzi', 'Bella Safira', 'Candra Kirana', 'Diana Puspita',
                'Eko Saputra', 'Fani Oktavia', 'Gilang Ramadhan', 'Hani Safitri',
                'Irfan Hakim', 'Jihan Aulia', 'Kevin Anggara', 'Lina Marlina',
                'Maulana Yusuf', 'Nisa Rahmawati', 'Oscar Pratama', 'Putri Ayu'
            ];
            
            $randomName = $randomNames[array_rand($randomNames)];
            $randomGender = ['Laki-laki', 'Perempuan'][array_rand(['Laki-laki', 'Perempuan'])];
            
            Tamu::create([
                'NIP' => '#tamu' . str_pad($tamuCounter, 3, '0', STR_PAD_LEFT),
                'nama_tamu' => $randomName,
                'jk' => $randomGender,
                'instansi' => 43, // Umum
                'agenda_id' => $randomAgenda->agenda_id,
            ]);
            $tamuCounter++;
        }
        
        $totalTamu = Tamu::count();
        $this->command->info("{$totalTamu} data tamu berhasil dibuat.");
        $this->command->info('- Tamu pegawai: ' . Tamu::whereNotLike('NIP', '#tamu%')->count());
        $this->command->info('- Tamu non-pegawai: ' . Tamu::whereLike('NIP', '#tamu%')->count());
    }
}