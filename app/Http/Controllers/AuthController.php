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
        // PERUBAHAN: Validasi untuk 'admin_id' bukan 'email'
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required|numeric',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('admin_id', 'password');

        // PERUBAHAN: Menggunakan guard 'admin' untuk proses otentikasi
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'admin_id' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->withInput($request->except('password'));
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
        // PERUBAHAN: Logout dari guard 'admin'
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}