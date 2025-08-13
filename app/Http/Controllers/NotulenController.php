<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Notulen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotulenController extends Controller
{
    /**
     * Menampilkan form untuk membuat notulen.
     * Jika notulen sudah ada, akan diarahkan ke halaman edit.
     */
    public function create(string $agendaId)
    {
        $agenda = Agenda::findOrFail($agendaId);
        $notulen = Notulen::where('agenda_id', $agendaId)->first();

        // Cek jika notulen untuk agenda ini sudah ada
        if ($notulen) {
            return redirect()->route('agenda.notulen.edit', ['agendaId' => $agendaId, 'notulen' => $notulen->id])
                ->with('info', 'Notulen untuk agenda ini sudah ada. Silakan edit.');
        }

        return view('notulen.create', compact('agenda'));
    }

    /**
     * Menyimpan notulen baru ke database.
     */
    public function store(Request $request, string $agendaId)
    {
        Agenda::findOrFail($agendaId);

        $validator = Validator::make($request->all(), [
            'pembuat' => 'required|string|max:255',
            'isi_notulen' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('agenda.notulen.create', $agendaId)
                ->withErrors($validator)
                ->withInput();
        }

        // Buat notulen baru dengan data dari request
        $notulen = new Notulen($request->all());
        $notulen->agenda_id = $agendaId;
        $notulen->save();

        // Redirect ke halaman detail agenda dengan pesan sukses
        return redirect()->route('agenda.show', ['agenda' => $agendaId])
            ->with('success', 'Notulen berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit notulen.
     */
    public function edit(string $agendaId, string $id)
    {
        $agenda = Agenda::findOrFail($agendaId);
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        
        return view('notulen.edit', compact('agenda', 'notulen'));
    }

    /**
     * Memperbarui data notulen yang ada di database.
     */
    public function update(Request $request, string $agendaId, string $id)
    {
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'pembuat' => 'required|string|max:255',
            'isi_notulen' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('agenda.notulen.edit', ['agendaId' => $agendaId, 'notulen' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $notulen->update($request->all());

        return redirect()->route('agenda.show', ['agenda' => $agendaId])
            ->with('success', 'Notulen berhasil diperbarui.');
    }

    /**
     * Menghapus notulen dari database.
     */
    public function destroy(string $agendaId, string $id)
    {
        $notulen = Notulen::where('agenda_id', $agendaId)->findOrFail($id);
        $notulen->delete();

        return redirect()->route('agenda.show', ['agenda' => $agendaId])
            ->with('success', 'Notulen berhasil dihapus.');
    }
}
