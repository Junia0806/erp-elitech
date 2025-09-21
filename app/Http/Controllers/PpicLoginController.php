<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PpicLoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function index()
    {
        return view('LoginPage');
    }

    /**
     * Menangani proses autentikasi (login attempt).
     */
    public function authenticate(Request $request)
    {
        // Mengambil nilai checkbox "remember me"
        $remember = $request->has('remember-me');

        // 1. Validasi input dari form
        $credentials = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required'],
        ]);

        // 2. Coba untuk melakukan login
        if (Auth::attempt($credentials, $remember)) {
            // Jika berhasil, regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke halaman yang dituju sebelumnya, atau ke dashboard jika tidak ada
            return redirect()->route('ppic.choose-item.index');
        }

        // 3. Jika login gagal
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
