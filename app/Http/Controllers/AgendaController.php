<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
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

        // ==================================================================
        // PERBAIKAN 1: Menangani filter berdasarkan status
        // ==================================================================
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'menunggu':
                    $query->menunggu();
                    break;
                case 'berlangsung':
                    $query->berlangsung();
                    break;
                case 'berakhir':
                    $query->berakhir();
                    break;
                // Jika status 'semua' atau lainnya, tidak perlu filter status
            }
        }

        // ==================================================================
        // PERBAIKAN 2: Logika pencarian multi-kolom
        // ==================================================================
        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_agenda', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tempat', 'like', '%' . $searchTerm . '%')
                    ->orWhere('dihadiri', 'like', '%' . $searchTerm . '%');
                // Jika Anda punya kolom 'opd' di database, uncomment baris di bawah
                // ->orWhere('opd', 'like', '%' . $searchTerm . '%');
            });
        }

        // Mengambil data setelah difilter, diurutkan, dan dipaginasi
        // Menambahkan appends() agar parameter filter & search tetap ada saat pindah halaman
        $agendas = $query->with('admin')
            ->latest('tanggal')
            ->paginate(10)
            ->appends($request->query());

        // Menghitung jumlah untuk kartu status
        $pendingAgendasCount = Agenda::menunggu()->count();
        $ongoingAgendasCount = Agenda::berlangsung()->count();
        $finishedAgendasCount = Agenda::berakhir()->count();

        // Mengirim data ke view yang benar (agenda.index atau landing)
        // Sesuaikan nama view di bawah ini jika perlu
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

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        $agenda->delete();

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil dihapus!');
    }
}