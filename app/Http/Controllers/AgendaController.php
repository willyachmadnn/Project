<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Agenda::query();
        
        // Filter agenda berdasarkan admin yang sedang login
        if (auth('admin')->check()) {
            $query->where('admin_id', auth('admin')->id());
        }

        // === SHOW/PER PAGE ===
        $perPage = (int) $request->input('perPage', 10);
        $allowed = [10, 25, 50, 100];
        if (!in_array($perPage, $allowed, true)) {
            $perPage = 10;
        }

        // ==================================================================
        // PERBAIKAN 1: Menangani filter berdasarkan status
        // ==================================================================
// PERBAIKAN 1: Menangani filter berdasarkan status (terima sinonim & angka)
        if ($request->filled('status')) {
            $s = strtolower((string) $request->status);

            // Jika angka 0/1/2
            if (is_numeric($s)) {
                switch ((int) $s) {
                    case 0:
                        $query->menunggu();
                        break;
                    case 1:
                        $query->berlangsung();
                        break;
                    case 2:
                        $query->berakhir();
                        break;
                }
            } else {
                // Jika teks: terima sinonim umum
                switch ($s) {
                    case 'menunggu':
                    case 'pending':
                    case 'menanti':
                        $query->menunggu();
                        break;

                    case 'berlangsung':
                    case 'ongoing':
                    case 'proses':
                        $query->berlangsung();
                        break;

                    case 'berakhir':
                    case 'selesai':   // ⟵ inilah kasus Anda
                    case 'done':
                        $query->berakhir();
                        break;

                    // selain itu: tidak difilter
                }
            }
        }


        // ==================================================================
        // PERBAIKAN 2: Logika pencarian multi-kolom termasuk admin dan tamu
        // ==================================================================
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // Pencarian di tabel agenda
                $q->where('nama_agenda', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tempat', 'like', '%' . $searchTerm . '%')
                    ->orWhere('dihadiri', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tanggal', 'like', '%' . $searchTerm . '%')
                    // Pencarian berdasarkan waktu (jam_mulai dan jam_selesai)
                    ->orWhereRaw("jam_mulai || ' - ' || jam_selesai LIKE ?",["%" . $searchTerm . "%"])
                    ->orWhere('jam_mulai', 'like', '%' . $searchTerm . '%')
                    ->orWhere('jam_selesai', 'like', '%' . $searchTerm . '%')
                    // Pencarian di tabel admin (relasi)
                    ->orWhereHas('admin', function($adminQuery) use ($searchTerm) {
                        $adminQuery->where('nama_admin', 'like', '%' . $searchTerm . '%')
                                  ->orWhere('opd_admin', 'like', '%' . $searchTerm . '%');
                    })
                    // Pencarian di tabel tamu (relasi)
                    ->orWhereHas('tamu', function($tamuQuery) use ($searchTerm) {
                        $tamuQuery->where('nama_tamu', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('instansi', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Data + paginate sesuai Show
        // PERBAIKAN: Urutkan berdasarkan status (menunggu dan berlangsung di awal, berakhir di akhir)
        // OPTIMASI: Eager load semua relasi yang diperlukan untuk menghindari N+1 queries
        $agendas = $query->with(['admin', 'tamu'])
            ->orderByRaw("
                CASE 
                    WHEN datetime(date(tanggal) || ' ' || jam_mulai) > ? THEN 1 -- Menunggu (prioritas tertinggi)
                    WHEN datetime(date(tanggal) || ' ' || jam_mulai) <= ? AND 
                        CASE 
                            WHEN time(jam_mulai) <= time(jam_selesai) 
                            THEN datetime(date(tanggal) || ' ' || jam_selesai) 
                            ELSE datetime(date(tanggal, '+1 day') || ' ' || jam_selesai) 
                        END >= ? THEN 2 -- Berlangsung (prioritas kedua)
                    ELSE 3 -- Berakhir (prioritas terendah)
                END
            ", [Carbon::now('Asia/Jakarta')->toDateTimeString(), Carbon::now('Asia/Jakarta')->toDateTimeString(), Carbon::now('Asia/Jakarta')->toDateTimeString()])
            ->latest() // Mengurutkan berdasarkan created_at dalam setiap kategori status
            ->paginate($perPage)   // ← gunakan nilai dari Show
            ->withQueryString();   // ← jaga query filter

        // Kartu status dengan caching untuk mengurangi beban database
        $adminId = auth('admin')->id();
        $pendingAgendasCount = cache()->remember("agenda_pending_count_{$adminId}", 300, function () use ($adminId) {
            return Agenda::where('admin_id', $adminId)->menunggu()->count();
        });
        $ongoingAgendasCount = cache()->remember("agenda_ongoing_count_{$adminId}", 300, function () use ($adminId) {
            return Agenda::where('admin_id', $adminId)->berlangsung()->count();
        });
        $finishedAgendasCount = cache()->remember("agenda_finished_count_{$adminId}", 300, function () use ($adminId) {
            return Agenda::where('admin_id', $adminId)->berakhir()->count();
        });

        return view('agenda.index', compact(
            'agendas',
            'pendingAgendasCount',
            'ongoingAgendasCount',
            'finishedAgendasCount'
        ));
    }

    // ... sisa method lainnya tidak perlu diubah ...

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agenda.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_agenda' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'dihadiri' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['admin_id'] = auth('admin')->id();

        Agenda::create($data);

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        $agenda->load(['admin', 'notulen', 'tamu']);
        return view('agenda.show', compact('agenda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agenda $agenda)
    {
        return view('agenda.edit', compact('agenda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agenda $agenda)
    {
        // Verifikasi apakah user yang login adalah pembuat agenda
        if ($agenda->admin_id !== auth('admin')->id()) {
            return redirect()->route('agenda.show', $agenda)
                ->with('error', 'Tidak dapat mengubah agenda karena anda bukan pembuat agenda.');
        }

        $validator = Validator::make($request->all(), [
            'nama_agenda' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'dihadiri' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $agenda->update($request->all());

        return redirect()->route('agenda.show', $agenda)
            ->with('success', 'Agenda berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        // Verifikasi apakah user yang login adalah pembuat agenda
        if ($agenda->admin_id !== auth('admin')->id()) {
            return redirect()->route('agenda.show', $agenda)
                ->with('error', 'Agenda tidak dapat dihapus karena anda bukan pembuat agenda.');
        }

        $agenda->delete();

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil dihapus!');
    }
}