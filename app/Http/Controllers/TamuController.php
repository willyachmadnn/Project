<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TamuController extends Controller
{
    /**
     * Menampilkan daftar tamu untuk agenda tertentu.
     */
    public function index(string $agendaId)
    {
        $agenda = Agenda::findOrFail($agendaId);
        // Mengambil semua tamu yang berelasi dengan agenda_id
        $tamu = Tamu::where('agenda_id', $agendaId)->get();

        // Mengirim data agenda dan tamu ke view
        return view('tamu.index', compact('agenda', 'tamu'));
    }

    /**
     * Menyimpan tamu baru ke dalam database.
     * Method ini dipanggil dari modal "Tambah Tamu".
     */
    public function store(Request $request, string $agendaId)
    {
        // Memastikan agenda yang dituju ada
        Agenda::findOrFail($agendaId);

        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            // NIP wajib diisi, harus 8 karakter, dan unik di tabel tamu
            'NIP' => 'required|string|size:8|unique:tamu,NIP',
            'nama_tamu' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            // Jenis kelamin (jk) harus salah satu dari 'Laki-laki' atau 'Perempuan'
            'jk' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, kembali ke halaman sebelumnya dengan error dan input lama
            return redirect()->route('agenda.tamu.index', $agendaId)
                ->withErrors($validator)
                ->withInput();
        }

        // Membuat instance Tamu baru dan mengisi data
        $tamu = new Tamu($request->all());
        $tamu->agenda_id = $agendaId;
        $tamu->save();

        // Redirect kembali ke halaman index tamu dengan pesan sukses
        return redirect()->route('agenda.tamu.index', $agendaId)
            ->with('success', 'Tamu berhasil ditambahkan.');
    }

    /**
     * Memperbarui data tamu yang sudah ada.
     * Method ini dipanggil dari modal "Edit Tamu".
     *
     * @param string $agendaId ID dari agenda
     * @param string $nip NIP dari tamu yang akan diupdate
     */
    public function update(Request $request, string $agendaId, string $nip)
    {
        // Cari tamu berdasarkan NIP dan agenda_id
        $tamu = Tamu::where('agenda_id', $agendaId)->findOrFail($nip);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_tamu' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'jk' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
        ]);

        if ($validator->fails()) {
            return redirect()->route('agenda.tamu.index', $agendaId)
                ->withErrors($validator)
                ->withInput();
        }

        // Update data tamu dengan data baru dari request
        $tamu->update($request->only(['nama_tamu', 'instansi', 'jk']));

        return redirect()->route('agenda.tamu.index', $agendaId)
            ->with('success', 'Data tamu berhasil diperbarui.');
    }

    /**
     * Menghapus data tamu dari database.
     *
     * @param string $agendaId ID dari agenda
     * @param string $nip NIP dari tamu yang akan dihapus
     */
    public function destroy(string $agendaId, string $nip)
    {
        // Cari tamu berdasarkan NIP dan agenda_id, lalu hapus
        $tamu = Tamu::where('agenda_id', $agendaId)->findOrFail($nip);
        $tamu->delete();

        return redirect()->route('agenda.tamu.index', $agendaId)
            ->with('success', 'Tamu berhasil dihapus.');
    }
}
