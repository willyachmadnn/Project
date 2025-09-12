<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Agenda;
use Carbon\Carbon;
use Database\Factories\AdminFactory;
use Database\Factories\AgendaFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat 1 admin untuk setiap OPD yang ada di tabel opd.
     * Menggunakan transaksi database untuk memastikan konsistensi data.
     */
    public function run(): void
    {
        // Gunakan transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        
        try {
            // Pastikan tabel OPD sudah terisi
            $opdCount = DB::table('opd')->count();
            if ($opdCount === 0) {
                $this->command->error('Tabel OPD kosong! Jalankan OpdSeeder terlebih dahulu.');
                return;
            }
            
            // Hapus semua data terkait agenda terlebih dahulu untuk menghindari constraint violations
            // Karena tabel tamu dan notulen memiliki foreign key ke agenda dengan onDelete cascade,
            // kita cukup menghapus agenda dan data terkait akan terhapus secara otomatis
            $this->command->info("Menghapus data agenda yang ada...");
            Agenda::query()->delete();
            
            // Hapus semua admin yang ada untuk menghindari duplikasi
            $this->command->info("Menghapus data admin yang ada...");
            Admin::query()->delete();
            
            // Ambil semua OPD dari database
            $opds = DB::table('opd')->orderBy('opd_id')->get();
            
            // Daftar nama admin Indonesia
            $namaDepan = [
                'Adi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gunawan', 'Hesti', 'Irfan', 'Joko', 
                'Kartika', 'Lina', 'Muhamad', 'Nina', 'Oki', 'Putri', 'Rudi', 'Siti', 'Tono', 'Umi', 
                'Vina', 'Wawan', 'Yanti', 'Zainal', 'Agus', 'Bambang', 'Dian', 'Endang', 'Faisal', 'Gita', 
                'Hendra', 'Indah', 'Jaya', 'Kurnia', 'Laras', 'Maman', 'Nita', 'Pandu', 'Ratna', 'Surya', 
                'Tuti', 'Udin', 'Vera', 'Wati', 'Yuda', 'Zulfa', 'Anwar', 'Bayu', 'Cahya', 'Dodi',
                'Ahmad', 'Sari', 'Rina', 'Dani', 'Maya', 'Reza', 'Sinta'
            ];
            
            $namaBelakang = [
                'Wijaya', 'Kusuma', 'Hidayat', 'Nugraha', 'Saputra', 'Santoso', 'Wibowo', 'Susanto', 'Setiawan', 'Pratama', 
                'Putra', 'Sari', 'Utami', 'Lestari', 'Suryadi', 'Hartono', 'Permadi', 'Irawan', 'Gunawan', 'Ramadhan', 
                'Firmansyah', 'Budiman', 'Hakim', 'Wahyudi', 'Sulistyo', 'Purnama', 'Handoko', 'Sugiarto', 'Mulya', 'Prabowo', 
                'Suryanto', 'Wicaksono', 'Nugroho', 'Hermawan', 'Kurniawan', 'Pranoto', 'Widodo', 'Sudrajat', 'Maulana', 'Ardiansyah',
                'Suharto', 'Rahayu', 'Indrawati', 'Mahendra', 'Kartini', 'Suhendi', 'Marlina'
            ];
            
            // Buat 1 admin untuk setiap OPD
            $this->command->info("Membuat 1 admin untuk setiap OPD...");
            $admins = collect();
            
            foreach ($opds as $index => $opd) {
                // Generate nama admin yang unik
                $namaAdmin = $namaDepan[$index % count($namaDepan)] . ' ' . $namaBelakang[$index % count($namaBelakang)];
                
                // Buat admin baru
                $admin = Admin::create([
                    'admin_id' => 10 + $opd->opd_id, // Admin ID dimulai dari 1001, 1002, dst
                    'nama_admin' => $namaAdmin,
                    'opd_admin' => $opd->opd_id, // Foreign key ke tabel opd
                    'password' => Hash::make('admin123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $admins->push($admin);
            }
            
            $this->command->info("Berhasil membuat " . $admins->count() . " admin OPD.");

            $this->command->info("Membuat 7 agenda untuk setiap admin OPD menggunakan AgendaFactory...");
            $bar = $this->command->getOutput()->createProgressBar($admins->count() * 7);
            $bar->start();
            
            // Untuk setiap admin, buat 7 agenda menggunakan factory
            foreach ($admins as $admin) {
                // Buat 7 agenda untuk admin ini menggunakan factory
                // dan atur admin_id secara eksplisit
                Agenda::factory()
                    ->count(7)
                    ->state(function (array $attributes) use ($admin) {
                        // Tambahkan nama admin dan OPD ke nama agenda untuk memudahkan identifikasi
                        $namaAgenda = $attributes['nama_agenda'];
                        
                        // Buat status acak: 40% Selesai (masa lalu), 30% Menunggu (masa depan), 30% Berlangsung (hari ini)
                        $statusRandom = rand(1, 100);
                        
                        if ($statusRandom <= 40) {
                            // Status 'Selesai' - agenda di masa lalu
                            $tanggal = Carbon::now()->subDays(rand(1, 90));
                        } elseif ($statusRandom <= 70) {
                            // Status 'Menunggu' - agenda di masa depan
                            $tanggal = Carbon::now()->addDays(rand(1, 60));
                        } else {
                            // Status 'Berlangsung' - agenda hari ini
                            $tanggal = Carbon::now();
                        }
                        
                        return [
                            'nama_agenda' => $namaAgenda,
                            'tanggal' => $tanggal,
                            'admin_id' => $admin->admin_id,
                        ];
                    })
                    ->create();
                
                // Advance progress bar by 7 (number of agendas created for this admin)
                $bar->advance(7);
            }
            
            $bar->finish();
            $this->command->info("\nBerhasil membuat " . ($admins->count() * 7) . " agenda untuk " . $admins->count() . " admin OPD.");
        
        // Commit transaksi jika semua operasi berhasil
        DB::commit();
        $this->command->info("Seeding AdminSeeder berhasil dilakukan.");
        
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            $this->command->error("Error saat seeding agenda: " . $e->getMessage());
            $this->command->error("Seeding AdminSeeder gagal, semua perubahan dibatalkan.");
            throw $e; // Re-throw exception untuk menampilkan stack trace jika diperlukan
        }
    }
}