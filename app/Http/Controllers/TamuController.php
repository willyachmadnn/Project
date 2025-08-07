<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TamuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $agendaId)
    {
        $agenda = Agenda::findOrFail($agendaId);
        $tamu = Tamu::where('agenda_id', $agendaId)->get();

        return view('tamu.index', compact('agenda', 'tamu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $agendaId)
    {
        $agenda = Agenda::findOrFail($agendaId);
        return view('tamu.create', compact('agenda'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $agendaId)
    {
        Agenda::findOrFail($agendaId);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->route('agenda.tamu.create', $agendaId)
                ->withErrors($validator)
                ->withInput();
        }

        $tamu = new Tamu($request->all());
        $tamu->agenda_id = $agendaId;
        $tamu->save();

        return redirect()->route('agenda.tamu.index', $agendaId)
            ->with('success', 'Tamu berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $agendaId, string $id)
    {
        $agenda = Agenda::findOrFail($agendaId);
        $tamu = Tamu::where('agenda_id', $agendaId)->findOrFail($id);

        return view('tamu.edit', compact('agenda', 'tamu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $agendaId, string $id)
    {
        $tamu = Tamu::where('agenda_id', $agendaId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            // PERBAIKAN KECIL: Membuat parameter eksplisit dalam array asosiatif
            return redirect()->route('agenda.tamu.edit', ['agendaId' => $agendaId, 'id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $tamu->update($request->all());

        return redirect()->route('agenda.tamu.index', $agendaId)
            ->with('success', 'Tamu berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $agendaId, string $id)
    {
        $tamu = Tamu::where('agenda_id', $agendaId)->findOrFail($id);
        $tamu->delete();

        return redirect()->route('agenda.tamu.index', $agendaId)
            ->with('success', 'Tamu berhasil dihapus');
    }

    /**
     * Update kehadiran tamu.
     */
    public function updateKehadiran(Request $request, string $agendaId, string $id)
    {
        $tamu = Tamu::where('agenda_id', $agendaId)->findOrFail($id);
        $tamu->kehadiran = $request->input('kehadiran', false); // Default ke false jika tidak ada
        $tamu->save();

        return redirect()->route('agenda.tamu.index', $agendaId)
            ->with('success', 'Status kehadiran berhasil diperbarui');
    }
}