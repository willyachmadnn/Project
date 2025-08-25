<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Agenda;
use Carbon\Carbon;
use Database\Factories\AdminFactory;
use Database\Factories\AgendaFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat admin menggunakan AdminFactory dan 15 agenda untuk setiap admin OPD menggunakan AgendaFactory.
     * Menggunakan transaksi database untuk memastikan konsistensi data.
     */
    public function run(): void
    {
        // Gunakan transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        
        try {
            // Hapus semua data terkait agenda terlebih dahulu untuk menghindari constraint violations
            // Karena tabel tamu dan notulen memiliki foreign key ke agenda dengan onDelete cascade,
            // kita cukup menghapus agenda dan data terkait akan terhapus secara otomatis
            $this->command->info("Menghapus data agenda yang ada...");
            Agenda::query()->delete();
            
            // Hapus semua admin yang ada untuk menghindari duplikasi
            $this->command->info("Menghapus data admin yang ada...");
            Admin::query()->delete();
            
            // Buat 10 admin baru menggunakan factory
            $this->command->info("Membuat admin menggunakan AdminFactory...");
            $admins = Admin::factory()->count(57)->create();
            
            $this->command->info("Berhasil membuat " . $admins->count() . " admin OPD.");

        $this->command->info("Membuat 15 agenda untuk setiap admin OPD menggunakan AgendaFactory...");
        $bar = $this->command->getOutput()->createProgressBar(count($admins) * 15);
        $bar->start();
        
        // Untuk setiap admin, buat 15 agenda menggunakan factory
        foreach ($admins as $admin) {
            // Buat 15 agenda untuk admin ini menggunakan factory
            // dan atur admin_id secara eksplisit
            Agenda::factory()
                ->count(15)
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
            
            // Advance progress bar by 15 (number of agendas created for this admin)
            $bar->advance(15);
        }
        
        $bar->finish();
        $this->command->info("\nBerhasil membuat " . (count($admins) * 15) . " agenda untuk " . count($admins) . " admin OPD.");
        
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