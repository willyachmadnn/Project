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
        // --- 1. PENGHITUNGAN UNTUK KARTU STATUS (DIPENGARUHI SLIDER) ---
        $countQuery = Agenda::query();
        $timeRange = $request->input('timeRange', '5');

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
        $pendingAgendasCount = (clone $countQuery)->menunggu()->count();
        $ongoingAgendasCount = (clone $countQuery)->berlangsung()->count();
        $finishedAgendasCount = (clone $countQuery)->berakhir()->count();

        // --- 2. QUERY UNTUK TABEL DAFTAR AGENDA ---
        // PERBAIKAN 1: Tambahkan with('admin') untuk Eager Loading
        $agendasQuery = Agenda::with('admin');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $agendasQuery->where(function ($q) use ($search) {
                $q->where('nama_agenda', 'like', '%' . $search . '%')
                    ->orWhere('tempat', 'like', '%' . $search . '%')
                    // PERBAIKAN 2: Tambahkan pencarian berdasarkan nama admin (PIC)
                    ->orWhereHas('admin', function ($adminQuery) use ($search) {
                        $adminQuery->where('nama_admin', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            match ($status) {
                'menunggu' => $agendasQuery->menunggu(),
                'berlangsung' => $agendasQuery->berlangsung(),
                'berakhir' => $agendasQuery->berakhir(),
                default => null,
            };
        }

        $perPage = $request->input('perPage', 10);
        $agendas = $agendasQuery->latest('tanggal')->paginate($perPage)->withQueryString();

        return view('landing', [
            'agendas' => $agendas,
            'pendingAgendasCount' => $pendingAgendasCount,
            'ongoingAgendasCount' => $ongoingAgendasCount,
            'finishedAgendasCount' => $finishedAgendasCount,
        ]);
    }

    /**
     * Menangani permintaan API untuk slider.
     */
    public function getAgendaCounts(Request $request): JsonResponse
    {
        $countQuery = Agenda::query();
        $timeRange = $request->input('timeRange', '5');

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