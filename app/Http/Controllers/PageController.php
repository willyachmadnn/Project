<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class PageController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page).
     */
    public function index(Request $request): View
    {
        // --- 1. PENGHITUNGAN UNTUK KARTU STATUS ---
        $countQuery = Agenda::query();
        $timeRange = $request->input('timeRange', '5');
        
        // Filter agenda berdasarkan admin yang sedang login
        if (auth('admin')->check()) {
            $adminId = auth('admin')->id();
            $countQuery->where('admin_id', $adminId);
        }

        if ($timeRange != '5') {
            $startDate = null;
            switch ($timeRange) {
                case '1':
                    $startDate = Carbon::now('Asia/Jakarta')->startOfDay();
                    break;
                case '2':
                    $startDate = Carbon::now('Asia/Jakarta')->subDays(6)->startOfDay();
                    break;
                case '3':
                    $startDate = Carbon::now('Asia/Jakarta')->subDays(29)->startOfDay();
                    break;
                case '4':
                    $startDate = Carbon::now('Asia/Jakarta')->subYear()->addDay()->startOfDay();
                    break;
            }
            if ($startDate) {
                $countQuery->where('tanggal', '>=', $startDate);
            }
        }
        // Cache status counts dengan key yang berbeda berdasarkan timeRange dan admin_id
        $adminId = auth('admin')->check() ? auth('admin')->id() : 'guest';
        $cacheKey = "landing_counts_{$timeRange}_{$adminId}";
        $counts = cache()->remember($cacheKey, 300, function () use ($countQuery) {
            return [
                'pending' => (clone $countQuery)->menunggu()->count(),
                'ongoing' => (clone $countQuery)->berlangsung()->count(),
                'finished' => (clone $countQuery)->berakhir()->count(),
            ];
        });
        
        $pendingAgendasCount = $counts['pending'];
        $ongoingAgendasCount = $counts['ongoing'];
        $finishedAgendasCount = $counts['finished'];

        // --- 2. QUERY UNTUK TABEL DAFTAR AGENDA ---
        $agendasQuery = Agenda::query(); // Menggunakan query baru, bukan yang dari count
        
        // Filter agenda berdasarkan admin yang sedang login
        if (auth('admin')->check()) {
            $adminId = auth('admin')->id();
            $agendasQuery->where('admin_id', $adminId);
        }
        // Jika tidak login sebagai admin, tampilkan semua agenda (tidak perlu filter)

        // ==================================================================
        // PERBAIKAN UTAMA: Menambahkan kolom OPD dan Dihadiri ke dalam pencarian
        // ==================================================================
        if ($request->filled('search')) {
            $search = $request->input('search');
            $agendasQuery->where(function ($q) use ($search) {
                $q->where('nama_agenda', 'like', '%' . $search . '%')
                    ->orWhere('tempat', 'like', '%' . $search . '%')
                    ->orWhere('tanggal', 'like', '%' . $search . '%')
                    ->orWhere('waktu', 'like', '%' . $search . '%')
                    ->orWhere('jam_mulai', 'like', '%' . $search . '%')
                    ->orWhere('jam_selesai', 'like', '%' . $search . '%')
                    ->orWhereRaw("jam_mulai || ' - ' || jam_selesai LIKE ?",["%" . $search . "%"])
                    ->orWhereHas('admin', function($adminQuery) use ($search) {
                        $adminQuery->where('nama_admin', 'like', '%' . $search . '%')
                                  ->orWhere('opd_admin', 'like', '%' . $search . '%');
                    })
                    ->orWhere('dihadiri', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan status (Tidak ada perubahan)
        if ($request->filled('status')) {
            $status = $request->input('status');
            match ($status) {
                'menunggu', 'pending' => $agendasQuery->menunggu(),
                'berlangsung' => $agendasQuery->berlangsung(),
                'selesai', 'berakhir' => $agendasQuery->berakhir(),
                default => null,
            };
        }

        $perPage = $request->input('perPage', 10);
        // PERBAIKAN: Urutkan berdasarkan tanggal terbaru (kolom tanggal)
        // OPTIMASI: Eager load relasi untuk menghindari N+1 queries
        $agendas = $agendasQuery->with(['admin', 'tamu'])->orderBy('tanggal', 'desc')->paginate($perPage)->withQueryString();

        return view('landing', [
            'agendas' => $agendas,
            'pendingAgendasCount' => $pendingAgendasCount,
            'ongoingAgendasCount' => $ongoingAgendasCount,
            'finishedAgendasCount' => $finishedAgendasCount,
        ]);
    }

    /**
     * Menangani permintaan API untuk slider (jika masih digunakan).
     */
    public function getAgendaCounts(Request $request): JsonResponse
    {
        $countQuery = Agenda::query();
        $timeRange = $request->input('timeRange', '5');
        
        // Filter agenda berdasarkan admin yang sedang login
        if (auth('admin')->check()) {
            $countQuery->where('admin_id', auth('admin')->id());
        }

        if ($timeRange != '5') {
            $startDate = null;
            switch ($timeRange) {
                case '1':
                    $startDate = Carbon::now('Asia/Jakarta')->startOfDay();
                    break;
                case '2':
                    $startDate = Carbon::now('Asia/Jakarta')->subDays(6)->startOfDay();
                    break;
                case '3':
                    $startDate = Carbon::now('Asia/Jakarta')->subDays(29)->startOfDay();
                    break;
                case '4':
                    $startDate = Carbon::now('Asia/Jakarta')->subYear()->addDay()->startOfDay();
                    break;
            }
            if ($startDate) {
                $countQuery->where('tanggal', '>=', $startDate);
            }
        }

        return response()->json([
            'pending' => (clone $countQuery)->menunggu()->count(),
            'ongoing' => (clone $countQuery)->berlangsung()->count(),
            'finished' => (clone $countQuery)->berakhir()->count(),
        ]);
    }
}