<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Opd;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            // NIP wajib diisi, harus 8 karakter
            'NIP' => 'required|string|size:8',
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

        // Cek apakah NIP sudah terdaftar untuk agenda ini
        $existingTamu = Tamu::where('NIP', $request->NIP)
                            ->where('agenda_id', $agendaId)
                            ->first();
        
        if ($existingTamu) {
            return redirect()->route('agenda.tamu.index', $agendaId)
                ->withErrors(['NIP' => 'NIP sudah terdaftar untuk agenda ini'])
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
     * @param Tamu $tamu Model tamu yang akan diupdate (route model binding)
     */
    public function update(Request $request, string $agendaId, Tamu $tamu)
    {
        // Pastikan tamu berada di agenda yang benar
        if ($tamu->agenda_id != $agendaId) {
            abort(404);
        }

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
     * @param Tamu $tamu Model tamu yang akan dihapus (route model binding)
     */
    public function destroy(string $agendaId, Tamu $tamu)
    {
        // Pastikan tamu berada di agenda yang benar
        if ($tamu->agenda_id != $agendaId) {
            abort(404);
        }
        
        $tamu->delete();

        return redirect()->route('agenda.tamu.index', $agendaId)
            ->with('success', 'Tamu berhasil dihapus.');
    }

    /**
     * Menampilkan form registrasi tamu public dari QR code.
     */
    public function createPublic(Request $request)
    {
        $agendaId = $request->get('agenda_id');
        $type = $request->get('type');

        // Validasi parameter
        if (!$agendaId || !$type) {
            abort(400, 'Parameter agenda_id dan type diperlukan');
        }

        // Pastikan agenda exists
        $agenda = Agenda::findOrFail($agendaId);

        // Cek apakah agenda masih aktif
        if ($agenda->status === 'selesai') {
            return view('tamu.agenda-selesai', compact('agenda'));
        }

        // Tampilkan form sesuai type
        if ($type === 'pegawai') {
            return view('tamu.pegawai', compact('agendaId', 'type'));
        } elseif ($type === 'non-pegawai') {
            // Ambil semua data OPD untuk dropdown
            $opdList = Opd::orderBy('nama_opd', 'asc')->get();
            return view('tamu.non-pegawai', compact('agendaId', 'type', 'opdList'));
        }

        abort(400, 'Type tidak valid');
    }

    /**
     * Menyimpan data tamu dari form public QR code.
     */
    public function storePublic(Request $request)
    {
        // Validasi parameter wajib
        $validated = $request->validate([
            'agenda_id' => 'required|exists:agendas,agenda_id',
            'type' => 'required|in:pegawai,non-pegawai',
        ]);

        $agendaId = $validated['agenda_id'];
        $type = $validated['type'];

        // Pastikan agenda exists dan masih aktif
        $agenda = Agenda::findOrFail($agendaId);
        if ($agenda->status === 'selesai') {
            // Redirect back with an error message
            return redirect()->back()->withErrors(['general' => 'Maaf, agenda ini sudah selesai dan tidak menerima pendaftaran lagi.'])->withInput();
        }

        // Validasi berdasarkan type
        if ($type === 'pegawai') {
            $pegawaiValidator = Validator::make($request->all(), [
                'nip' => 'required|string|exists:pegawai,NIP',
            ], [
                'nip.required' => 'NIP wajib diisi.',
                'nip.exists' => 'Pegawai dengan NIP tersebut tidak ditemukan.',
            ]);

            if ($pegawaiValidator->fails()) {
                return redirect()->back()->withErrors($pegawaiValidator)->withInput();
            }

            // Cek apakah pegawai sudah terdaftar untuk agenda ini
            $existingTamu = Tamu::where('NIP', $request->nip)
                                ->where('agenda_id', $agendaId)
                                ->first();
            
            if ($existingTamu) {
                return redirect()->back()->withErrors(['nip' => 'Pegawai dengan NIP ini sudah terdaftar untuk agenda ini.'])->withInput();
            }

            // Ambil data pegawai dari tabel pegawai
            $pegawai = \App\Models\Pegawai::where('NIP', $request->nip)->first();
            
            // Simpan data tamu pegawai
            $tamu = Tamu::create([
                'NIP' => $request->nip,
                'nama_tamu' => $pegawai->nama_pegawai,
                'opd_id' => $pegawai->instansi,
                'instansi' => null, // Pegawai tidak menggunakan field instansi text
                'jk' => $pegawai->jk,
                'status' => 'pegawai', // Default status untuk pegawai
                'agenda_id' => $agendaId
            ]);

            // Redirect ke halaman sukses
            return redirect()->route('tamu.success', ['nip' => $tamu->NIP]);

        } elseif ($type === 'non-pegawai') {
            // Validasi dasar untuk non-pegawai
            $validationRules = [
                'nama' => 'required|string|max:255',
                'gender' => 'required|in:L,P',
                'status' => 'required|in:non-asn,umum',
            ];
            
            $validationMessages = [
                'nama.required' => 'Nama lengkap wajib diisi.',
                'gender.required' => 'Jenis kelamin wajib dipilih.',
                'status.required' => 'Status wajib dipilih.',
            ];
            
            // Jika status non-asn, instansi wajib diisi
            if ($request->status === 'non-asn') {
                $validationRules['instansi'] = 'required|string|max:255';
                $validationRules['opd_id'] = 'nullable|exists:opd,opd_id'; // Optional, harus valid jika diisi
                $validationMessages['instansi.required'] = 'Instansi wajib diisi untuk status Non-ASN.';
                $validationMessages['opd_id.exists'] = 'OPD yang dipilih tidak valid.';
            }

            $nonPegawaiValidator = Validator::make($request->all(), $validationRules, $validationMessages);

            if ($nonPegawaiValidator->fails()) {
                return redirect()->back()->withErrors($nonPegawaiValidator)->withInput();
            }

            // Cek apakah nama sudah terdaftar untuk agenda ini
            $existingTamu = Tamu::where('nama_tamu', $request->nama)
                                ->where('agenda_id', $agendaId)
                                ->first();

            if ($existingTamu) {
                return redirect()->back()->withErrors(['nama' => 'Tamu dengan nama ini sudah terdaftar untuk agenda ini.'])->withInput();
            }

            // Generate NIP unik untuk non-pegawai
            $nip = 'NP' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            while (Tamu::where('NIP', $nip)->exists()) {
                $nip = 'NP' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            }

            // Tentukan nilai opd_id dan instansi berdasarkan status
            $opdId = null;
            $instansiValue = null;
            
            if ($request->status === 'non-asn') {
                $instansiValue = $request->instansi;
                
                // Gunakan opd_id dari form jika tersedia (dari dropdown selection)
                if ($request->filled('opd_id')) {
                    $opdId = $request->opd_id;
                } else {
                    // Jika opd_id kosong, berarti input manual - tetap null
                    $opdId = null;
                }
            } elseif ($request->status === 'umum') {
                $instansiValue = null; // Umum tidak perlu instansi
            }

            // Simpan data tamu non-pegawai
            $tamu = Tamu::create([
                'NIP' => $nip,
                'nama_tamu' => $request->nama,
                'opd_id' => $opdId,
                'instansi' => $instansiValue,
                'jk' => $request->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                'status' => $request->status,
                'agenda_id' => $agendaId
            ]);

            // LOGIKA BARU: Validasi dan update status OPD untuk tamu Non-ASN
            if ($request->status === 'non-asn' && $opdId) {
                // Periksa apakah OPD yang dipilih terdaftar sebagai "OPD yang Diundang" pada agenda ini
                $opdDiundang = DB::table('agenda_opd')
                    ->where('agenda_id', $agendaId)
                    ->where('opd_id', $opdId)
                    ->first();
                
                if ($opdDiundang) {
                    // Jika OPD terdaftar sebagai diundang, update status dari "diundang" menjadi "hadir"
                    DB::table('agenda_opd')
                        ->where('agenda_id', $agendaId)
                        ->where('opd_id', $opdId)
                        ->update([
                            'status' => 'hadir',
                            'updated_at' => now()
                        ]);
                    
                    // Log untuk debugging (opsional)
                    Log::info("Status OPD berhasil diperbarui", [
                        'agenda_id' => $agendaId,
                        'opd_id' => $opdId,
                        'tamu_nama' => $request->nama,
                        'status_baru' => 'hadir'
                    ]);
                }
            }

            // Redirect ke halaman sukses
            return redirect()->route('tamu.success', ['nip' => $tamu->NIP]);
        }
        
        // Fallback for invalid type, though validation should prevent this
        return redirect()->back()->withErrors(['general' => 'Tipe tamu tidak valid.'])->withInput();
    }

    /**
     * Menampilkan halaman sukses setelah registrasi tamu.
     */
    public function success(Request $request)
    {
        $nip = $request->get('nip');
        
        if (!$nip) {
            abort(400, 'Parameter NIP diperlukan');
        }
        
        // Cari data tamu berdasarkan NIP
        $tamu = Tamu::where('NIP', $nip)->with('agenda')->first();
        
        if (!$tamu) {
            abort(404, 'Data tamu tidak ditemukan');
        }
        
        return view('tamu.success', compact('tamu'));
    }

    /**
     * Menampilkan halaman kelola tamu untuk agenda tertentu.
     */
    public function kelolaTamu(string $agendaId)
    {
        $agenda = Agenda::findOrFail($agendaId);
        
        // Mengambil semua tamu yang berelasi dengan agenda_id dengan relasi OPD
        $tamu = Tamu::where('agenda_id', $agendaId)
                    ->with('opd')
                    ->orderBy('nama_tamu', 'asc')
                    ->get();

        return view('tamu.kelola', compact('agenda', 'tamu'));
    }

    /**
     * Menampilkan halaman tambah OPD untuk agenda tertentu.
     */    public function tambahOpd(string $agendaId)
    {
        $agenda = Agenda::findOrFail($agendaId);
        
        // Mengambil OPD yang sudah ditambahkan ke agenda ini
        $opdSudahDitambahkan = DB::table('agenda_opd')
            ->where('agenda_id', $agendaId)
            ->pluck('opd_id')
            ->toArray();
        
        // Mengambil semua OPD kecuali yang sudah ditambahkan DAN KECUALI OPD ID 58
        $opds = \App\Models\Opd::whereNotIn('opd_id', $opdSudahDitambahkan)
                                ->where('opd_id', '!=', 58) // <--- TAMBAHKAN BARIS INI
                                ->orderBy('nama_opd', 'asc')
                                ->get();

        return view('tamu.tambah-opd', compact('agenda', 'opds'));
    }
    
    /**
     * Menyimpan OPD yang dipilih untuk agenda tertentu
     */
    public function simpanOpd(Request $request, $agendaId)
    {
        $request->validate([
            'opd_ids' => 'required|array',
            'opd_ids.*' => 'exists:opd,opd_id'
        ]);
        
        $agenda = Agenda::where('agenda_id', $agendaId)->firstOrFail();
        
        // Ambil OPD yang sudah ada untuk agenda ini
        $existingOpdIds = DB::table('agenda_opd')
            ->where('agenda_id', $agendaId)
            ->pluck('opd_id')
            ->toArray();
        
        // Filter OPD baru yang belum ada
        $newOpdIds = array_diff($request->opd_ids, $existingOpdIds);
        
        // Simpan hanya OPD yang baru dengan status default 'diundang'
        foreach ($newOpdIds as $opdId) {
            DB::table('agenda_opd')->insert([
                'agenda_id' => $agendaId,
                'opd_id' => $opdId,
                'status' => 'diundang', // Status default ketika OPD pertama kali diundang
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        $message = count($newOpdIds) > 0 
            ? 'OPD berhasil ditambahkan!' 
            : 'OPD yang dipilih sudah ada dalam daftar.';
        
        return redirect()->route('agenda.tamu.opd-diundang', $agendaId)
                        ->with('success', $message);
    }
    
    /**
     * Menampilkan daftar OPD yang diundang untuk agenda tertentu
     */
    public function opdDiundang($agendaId)
    {
        $agenda = Agenda::with('tamu')->where('agenda_id', $agendaId)->firstOrFail();
        
        $opdDiundang = DB::table('agenda_opd')
            ->join('opd', 'agenda_opd.opd_id', '=', 'opd.opd_id')
            ->where('agenda_opd.agenda_id', $agendaId)
            ->select('opd.*', 'agenda_opd.created_at as tanggal_diundang', 'agenda_opd.status')
            ->orderBy('opd.nama_opd')
            ->get();
            
        return view('tamu.opd-diundang', compact('agenda', 'opdDiundang'));
    }
    
    /**
     * Menghapus OPD dari agenda tertentu
     */
    public function hapusOpd($agendaId, $opdId)
    {
        $agenda = Agenda::where('agenda_id', $agendaId)->firstOrFail();
        
        // Hapus OPD dari agenda
        $deleted = DB::table('agenda_opd')
            ->where('agenda_id', $agendaId)
            ->where('opd_id', $opdId)
            ->delete();
            
        if ($deleted) {
            return redirect()->route('agenda.tamu.opd-diundang', $agendaId)
                            ->with('success', 'OPD berhasil dihapus dari daftar undangan!');
        } else {
            return redirect()->route('agenda.tamu.opd-diundang', $agendaId)
                            ->with('error', 'OPD tidak ditemukan dalam daftar undangan!');
        }
    }
}