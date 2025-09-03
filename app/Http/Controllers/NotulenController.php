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
    public function create(Agenda $agenda)
    {
        $notulen = Notulen::where('agenda_id', $agenda->agenda_id)->first();

        // Cek jika notulen untuk agenda ini sudah ada
        if ($notulen) {
            // DIUBAH: Menggunakan $agenda langsung
            return redirect()->route('agenda.notulen.edit', ['agenda' => $agenda, 'notulen' => $notulen])
                ->with('info', 'Notulen untuk agenda ini sudah ada. Silakan edit.');
        }

        return view('notulen.create', compact('agenda'));
    }

    /**
     * Menyimpan notulen baru ke database.
     */
    public function store(Request $request, Agenda $agenda)
    {
        $validator = Validator::make($request->all(), [
            'isi_notulen' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Buat notulen baru dengan data dari request
        $notulen = new Notulen();
        $notulen->agenda_id = $agenda->agenda_id;
        $notulen->isi_notulen = $request->isi_notulen;
        $notulen->pembuat = auth('admin')->user()->nama_admin;
        $notulen->save();

        // Redirect ke halaman detail agenda dengan pesan sukses
        return redirect()->route('agenda.show', ['agenda' => $agenda, 'tab' => 'notulen'])
            ->with('success', 'Notulen berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit notulen.
     */
    public function edit(Agenda $agenda, Notulen $notulen)
    {
        return view('notulen.edit', compact('agenda', 'notulen'));
    }

    /**
     * Memperbarui data notulen yang ada di database.
     */
    public function update(Request $request, Agenda $agenda, Notulen $notulen)
    {
        $validator = Validator::make($request->all(), [
            'isi_notulen' => 'required|string',
        ]);

        if ($validator->fails()) {
            // DIUBAH: Menggunakan $agenda dan $notulen langsung
            return redirect()->route('agenda.notulen.edit', ['agenda' => $agenda, 'notulen' => $notulen])
                ->withErrors($validator)
                ->withInput();
        }

        $notulenData = $request->only(['isi_notulen']);
        // Mengupdate nama pembuat dengan user yang sedang login saat update
        $notulenData['pembuat'] = auth('admin')->user()->nama_admin;

        $notulen->update($notulenData);

        return redirect()->route('agenda.show', ['agenda' => $agenda, 'tab' => 'notulen'])
            ->with('success', 'Notulen berhasil diperbarui.');
    }

    /**
     * Menghapus notulen dari database.
     */
    public function destroy(Agenda $agenda, Notulen $notulen)
    {
        $notulen->delete();

        return redirect()->route('agenda.show', ['agenda' => $agenda, 'tab' => 'notulen'])
            ->with('success', 'Notulen berhasil dihapus.');
    }
}