<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // Menggunakan guard 'admin' untuk memeriksa apakah sudah login
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        // Tetap pakai nama field 'admin_id' di form, tapi izinkan angka atau teks
        $data = $request->validate([
            'admin_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'admin_id.required' => 'Admin ID / Nama wajib diisi.',
        ]);

        $login = trim($data['admin_id']);
        $password = $data['password'];
        $remember = (bool) $request->boolean('remember', false);

        $guard = Auth::guard('admin');

        // 1) Jika input numerik → cek berdasarkan admin_id
        $ok = false;
        if (ctype_digit($login)) {
            $ok = $guard->attempt([
                'admin_id' => (int) $login,
                'password' => $password,
            ], $remember);
        }

        // 2) Jika gagal / bukan angka → cek berdasarkan nama_admin
        if (!$ok) {
            $ok = $guard->attempt([
                'nama_admin' => $login,
                'password' => $password,
            ], $remember);
        }

        if (!$ok) {
            return back()
                ->withErrors(['admin_id' => 'Admin ID / Nama atau password tidak cocok.'])
                ->withInput($request->except('password'));
        }

        // Sukses
        $request->session()->regenerate();
        
        // Dapatkan informasi admin yang login
        $admin = $guard->user();
        $adminName = $admin->nama_admin ?? 'Admin';
        $adminOpd = $admin->opd->nama_opd ?? 'OPD Tidak Diketahui';
        
        // Tambahkan pesan selamat datang
        return redirect()->intended(route('agenda.index'))
            ->with('success', "Selamat datang di SI-Agenda Kabupaten Mojokerto, {$adminName} dari {$adminOpd}"); // menampilkan index.blade dengan pesan selamat datang
    }



    /**
     * Show the dashboard page.
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        try {
            // Pastikan user sudah login sebelum logout
            if (Auth::guard('admin')->check()) {
                Auth::guard('admin')->logout();
            }
            
            // Invalidate session dengan error handling
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
            // Clear any cached authentication
            $request->session()->flush();
            
        } catch (\Exception $e) {
            // Log error tapi tetap redirect ke login
            \Log::error('Logout error: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('message', 'Anda telah berhasil logout.'); // <- kembali ke halaman login
    }

}