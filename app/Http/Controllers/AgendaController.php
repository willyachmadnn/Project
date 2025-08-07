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
    public function index()
    {
        $agendas = Agenda::latest()->get();
        return view('agenda.index', compact('agendas'));
    }

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
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Agenda::create($request->all());

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        // Memuat relasi notulen dan tamu
        $agenda->load(['notulen', 'tamu']);
        
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
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
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
        // Hapus tamu dan notulen terkait melalui relasi cascade
        $agenda->delete();

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil dihapus!');
    }

    /**
     * Display a listing of the resource for public view.
     */
    public function publicIndex()
    {
        $agendas = Agenda::latest()->get();
        return view('home', compact('agendas'));
    }
}
