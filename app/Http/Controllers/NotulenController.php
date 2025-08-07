<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Notulen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotulenController extends Controller
{
    public function create(string $agendaId)
    {
        $agenda = Agenda::findOrFail($agendaId);
        $notulen = Notulen::where('agenda_id', $agendaId)->first();
        if ($notulen) {
            return redirect()->route('agenda.notulen.edit', ['agendaId' => $agendaId, 'id' => $notulen->id])
                ->with('info', 'Notulen untuk agenda ini sudah ada. Silakan edit.');
        }
        return view('notulen.create', compact('agenda'));
    }

    public function store(Request $request, string $agendaId)
    {
        Agenda::findOrFail($agendaId);
        $validator = Validator::make($request->all(), [
            'isi_notulen' => 'required|string',
            'pembuat' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('agenda.notulen.create', $agendaId)->withErrors($validator)->withInput();
        }

        $notulen = new Notulen($request->all());
        $notulen->agenda_id = $agendaId;
        $notulen->save();

        // PERBAIKAN: Parameter dikirim sebagai array ['nama_parameter' => nilai]
        return redirect()
            ->route('agenda.show', ['agenda' => $agendaId])
            ->with('success', 'Notulen berhasil ditambahkan');
    }

    public function edit(string $agendaId, string $id)
    {
        $agenda = Agenda::findOrFail($agendaId);
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        return view('notulen.edit', compact('agenda', 'notulen'));
    }

    public function update(Request $request, string $agendaId, string $id)
    {
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'isi_notulen' => 'required|string',
            'pembuat' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('agenda.notulen.edit', ['agendaId' => $agendaId, 'id' => $id])->withErrors($validator)->withInput();
        }

        $notulen->update($request->all());

        // PERBAIKAN: Parameter dikirim sebagai array ['nama_parameter' => nilai]
        return redirect()
            ->route('agenda.show', ['agenda' => $agendaId])
            ->with('success', 'Notulen berhasil diperbarui');
    }

    public function destroy(string $agendaId, string $id)
    {
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        $notulen->delete();

        // PERBAIKAN: Parameter dikirim sebagai array ['nama_parameter' => nilai]
        return redirect()
            ->route('agenda.show', ['agenda' => $agendaId])
            ->with('success', 'Notulen berhasil dihapus');
    }
}