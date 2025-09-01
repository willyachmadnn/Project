<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Mencari pegawai berdasarkan NIP
     * 
     * @param string $nip
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $nip)
    {
        // Validasi format NIP (18 karakter)
        if (strlen($nip) !== 18) {
            return response()->json([
                'success' => false,
                'message' => 'Format NIP tidak valid. NIP harus 18 karakter.'
            ], 400);
        }

        // Cari pegawai berdasarkan NIP
        $pegawai = Pegawai::with('opd')->where('NIP', $nip)->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai dengan NIP tersebut tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'NIP' => $pegawai->NIP,
                'nama_pegawai' => $pegawai->nama_pegawai,
                'jk' => $pegawai->jk,
                'instansi' => $pegawai->instansi,
                'opd' => $pegawai->opd ? $pegawai->opd->nama_opd : null
            ]
        ]);
    }
}