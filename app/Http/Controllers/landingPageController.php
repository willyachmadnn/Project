<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

/**
 * Controller ini menangani semua halaman yang dapat diakses oleh publik,
 * seperti halaman utama (landing page) dan halaman detail agenda.
 */
class PageController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page) dengan daftar agenda yang difilter dan dipaginasi.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // --- PENGHITUNGAN JUMLAH STATUS UNTUK KARTU (DIPENGARUHI OLEH FILTER WAKTU) ---

        // Memulai query dasar untuk menghitung jumlah agenda.
        $countQuery = Agenda::query();

        // Menerapkan filter rentang waktu (timeRange) dari slider HANYA untuk query hitungan ini.
        if ($request->filled('timeRange') && $request->input('timeRange') != '5') {
            $timeRange = $request->input('timeRange');
            $endDate = Carbon::now('Asia/Jakarta');
            $startDate = null;

            switch ($timeRange) {
                case '1': $startDate = $endDate->copy()->startOfDay(); break;
                case '2': $startDate = $endDate->copy()->subDays(6)->startOfDay(); break;
                case '3': $startDate = $endDate->copy()->subDays(29)->startOfDay(); break;
                case '4': $startDate = $endDate->copy()->subYear()->startOfDay(); break;
            }

            if ($startDate) {
                $countQuery->whereDate('tanggal', '>=', $startDate);
            }
        }

        // Menghitung jumlah agenda untuk setiap status menggunakan Query Scopes dari model Agenda.
        $pendingAgendasCount = (clone $countQuery)->menunggu()->count();
        $ongoingAgendasCount = (clone $countQuery)->berlangsung()->count();
        $finishedAgendasCount = (clone $countQuery)->berakhir()->count();


        // --- QUERY UTAMA UNTUK MENAMPILKAN TABEL DAFTAR AGENDA ---

        // Memulai query baru untuk mengambil data yang akan ditampilkan di tabel.
        // `with('admin')` digunakan untuk Eager Loading, mengoptimalkan query dengan mengambil data admin terkait dalam satu kali panggilan.
        $agendasQuery = Agenda::with('admin');

        // Menerapkan filter pencarian jika ada input 'search'.
        if ($request->filled('search')) {
            $search = $request->input('search');
            $agendasQuery->where(function ($q) use ($search) {
                $q->where('nama_agenda', 'like', '%' . $search . '%')
                  ->orWhere('tempat', 'like', '%' . $search . '%');
            });
        }

        // Menerapkan filter status jika ada input 'status'.
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status == 'menunggu') $agendasQuery->menunggu();
            elseif ($status == 'berlangsung') $agendasQuery->berlangsung();
            elseif ($status == 'berakhir') $agendasQuery->berakhir();
        }

        // Mengambil nilai 'perPage' dari request, dengan nilai default 10.
        $perPage = in_array($request->input('perPage'), [10, 20, 50, 100])
            ? $request->input('perPage')
            : 10;

        // Melakukan paginasi, mengurutkan data, dan mempertahankan semua parameter filter di URL.
        // `onEachSide(2)` memastikan ada 2 link halaman di setiap sisi halaman aktif.
        $agendas = $agendasQuery->latest('tanggal')->latest('jam_mulai')->paginate($perPage)->onEachSide(2)->withQueryString();

        // Mengirim semua data yang diperlukan ke view 'landing'.
        return view('landing', [
            'agendas' => $agendas,
            'pendingAgendasCount' => $pendingAgendasCount,
            'ongoingAgendasCount' => $ongoingAgendasCount,
            'finishedAgendasCount' => $finishedAgendasCount,
        ]);
    }

    /**
     * Menampilkan halaman detail untuk satu agenda spesifik.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\View\View
     */
    public function show(Agenda $agenda): View
    {
        // Menggunakan Route Model Binding, Laravel secara otomatis akan mencari agenda
        // berdasarkan ID yang ada di URL dan mengirimkannya sebagai objek $agenda.
        return view('agenda.show', [
            'agenda' => $agenda
        ]);
    }

    /**
     * Menangani permintaan AJAX dari slider untuk memperbarui jumlah status agenda secara dinamis.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgendaCounts(Request $request): JsonResponse
    {
        // Logika ini sama dengan yang ada di method index untuk menghitung jumlah,
        // memastikan konsistensi data antara pemuatan halaman awal dan pembaruan dinamis.
        $countQuery = Agenda::query();

        if ($request->filled('timeRange') && $request->input('timeRange') != '5') {
            $timeRange = $request->input('timeRange');
            $endDate = Carbon::now('Asia/Jakarta');
            $startDate = null;

            switch ($timeRange) {
                case '1': $startDate = $endDate->copy()->startOfDay(); break;
                case '2': $startDate = $endDate->copy()->subDays(6)->startOfDay(); break;
                case '3': $startDate = $endDate->copy()->subDays(29)->startOfDay(); break;
                case '4': $startDate = $endDate->copy()->subYear()->startOfDay(); break;
            }

            if ($startDate) {
                $countQuery->whereDate('tanggal', '>=', $startDate);
            }
        }

        // Mengembalikan data dalam format JSON yang akan dibaca oleh JavaScript.
        return response()->json([
            'pending' => (clone $countQuery)->menunggu()->count(),
            'ongoing' => (clone $countQuery)->berlangsung()->count(),
            'finished' => (clone $countQuery)->berakhir()->count(),
        ]);
    }
}
