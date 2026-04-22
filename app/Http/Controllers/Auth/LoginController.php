<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Identitas (Username/NIM) wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = $request->username;
        $password   = $request->password;

        // Cari user berdasarkan username ATAU berdasarkan nim di tabel identitas mahasiswa
        $user = \App\Models\User::where('username', $loginInput)
            ->orWhereHas('mahasiswa', function ($query) use ($loginInput) {
                $query->where('nim', $loginInput);
            })
            ->first();

        // Jika user eksis, delegasikan verifikasi password ke fitur bawaan Auth::attempt
        if ($user && Auth::attempt(['username' => $user->username, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Login berhasil, selamat datang ' . Auth::user()->nama_lengkap);
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Identitas (Username / NIM) atau password Anda salah.']);
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
