<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProduksiLoginController extends Controller
{
    public function index()
    {
        return view('ProductionLoginPage');
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
            $user = Auth::user();

            // Asumsi: Model User Anda memiliki kolom bernama 'role'.
            // Sesuaikan nama role ('manager', 'staff') dengan yang ada di database Anda.
            if ($user->role === 'Manager Produksi') {
                // Jika role adalah 'manager', arahkan ke halaman history (sebagai contoh)
                return redirect()->route('produksi.manager.verification.index');
            } else if ($user->role === 'Staff Produksi') {
                // Jika role adalah 'staff', arahkan ke halaman choose item
                return redirect()->route('produksi.staff.tasks.index');
            }

            return redirect()->route('produksi.login.index');
        }

        // 3. Jika login gagal
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
