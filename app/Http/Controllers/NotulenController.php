<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Notulen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotulenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tidak digunakan karena notulen selalu terkait dengan agenda tertentu
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $agendaId)
    {
        $agenda = Agenda::findOrFail($agendaId);
        
        // Cek apakah notulen sudah ada
        $notulen = Notulen::where('agenda_id', $agendaId)->first();
        if ($notulen) {
            return redirect()->route('agenda.notulen.edit', [$agendaId, $notulen->id])
                ->with('info', 'Notulen untuk agenda ini sudah ada. Silakan edit notulen yang ada.');
        }
        
        return view('notulen.create', [
            'agenda' => $agenda
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $agendaId)
    {
        $agenda = Agenda::findOrFail($agendaId);
        
        // Cek apakah notulen sudah ada
        $existingNotulen = Notulen::where('agenda_id', $agendaId)->first();
        if ($existingNotulen) {
            return redirect()->route('agenda.notulen.edit', [$agendaId, $existingNotulen->id])
                ->with('info', 'Notulen untuk agenda ini sudah ada. Silakan edit notulen yang ada.');
        }
        
        $validator = Validator::make($request->all(), [
            'isi_notulen' => 'required|string',
            'pembuat' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('agenda.notulen.create', $agendaId)
                ->withErrors($validator)
                ->withInput();
        }

        $notulen = new Notulen($request->all());
        $notulen->agenda_id = $agendaId;
        $notulen->save();

        return redirect()
            ->route('agenda.show', $agendaId)
            ->with('success', 'Notulen berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $agendaId, string $id)
    {
        $agenda = Agenda::findOrFail($agendaId);
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        
        return view('notulen.show', [
            'agenda' => $agenda,
            'notulen' => $notulen
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $agendaId, string $id)
    {
        $agenda = Agenda::findOrFail($agendaId);
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        
        return view('notulen.edit', [
            'agenda' => $agenda,
            'notulen' => $notulen
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $agendaId, string $id)
    {
        $agenda = Agenda::findOrFail($agendaId);
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'isi_notulen' => 'required|string',
            'pembuat' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('agenda.notulen.edit', [$agendaId, $id])
                ->withErrors($validator)
                ->withInput();
        }

        $notulen->update($request->all());

        return redirect()
            ->route('agenda.show', $agendaId)
            ->with('success', 'Notulen berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $agendaId, string $id)
    {
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        $notulen->delete();

        return redirect()
            ->route('agenda.show', $agendaId)
            ->with('success', 'Notulen berhasil dihapus');
    }
}
