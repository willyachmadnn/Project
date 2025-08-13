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
        return redirect()->intended(route('agenda.index')); // menampilkan index.blade (sesuai setup kamu)
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
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // <- kembali ke halaman login
    }

}