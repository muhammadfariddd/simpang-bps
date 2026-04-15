<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Daftar semua mahasiswa.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::with('user')
            ->latest()
            ->paginate(15);

        return view('pages.users.index', compact('mahasiswas'));
    }

    /**
     * Form tambah mahasiswa.
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Simpan mahasiswa baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'username'       => 'required|string|unique:users,username|max:100',
            'email'          => 'nullable|email|unique:users,email',
            'password'       => 'required|string|min:6',
            'nim'            => 'required|string|unique:mahasiswa,nim',
            'universitas'    => 'required|string|max:255',
            'jurusan'        => 'nullable|string|max:255',
            'divisi'         => 'nullable|string|max:255',
            'target_proyek'  => 'nullable|string|max:500',
            'periode_mulai'  => 'required|date',
            'periode_selesai'=> 'required|date|after:periode_mulai',
        ], [
            'nim.unique'           => 'NIM sudah terdaftar.',
            'username.unique'      => 'Username sudah digunakan.',
            'periode_selesai.after'=> 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'peran'        => 'mahasiswa',
            'is_active'    => true,
        ]);

        Mahasiswa::create([
            'user_id'         => $user->id,
            'nim'             => $request->nim,
            'universitas'     => $request->universitas,
            'jurusan'         => $request->jurusan,
            'divisi'          => $request->divisi,
            'target_proyek'   => $request->target_proyek,
            'periode_mulai'   => $request->periode_mulai,
            'periode_selesai' => $request->periode_selesai,
            'status'          => 'aktif',
        ]);

        return redirect()->route('users.index')
            ->with('success', "Mahasiswa {$user->nama_lengkap} berhasil didaftarkan.");
    }

    /**
     * Detail mahasiswa.
     */
    public function show($id)
    {
        $mahasiswa = Mahasiswa::with(['user', 'logbooks', 'presensis', 'projeks', 'penilaian'])
            ->findOrFail($id);

        return view('pages.users.show', compact('mahasiswa'));
    }

    /**
     * Form edit mahasiswa.
     */
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        return view('pages.users.edit', compact('mahasiswa'));
    }

    /**
     * Update mahasiswa.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        $user      = $mahasiswa->user;

        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'email'           => "nullable|email|unique:users,email,{$user->id}",
            'nim'             => "required|string|unique:mahasiswa,nim,{$mahasiswa->id}",
            'universitas'     => 'required|string|max:255',
            'jurusan'         => 'nullable|string|max:255',
            'divisi'          => 'nullable|string|max:255',
            'target_proyek'   => 'nullable|string|max:500',
            'periode_mulai'   => 'required|date',
            'periode_selesai' => 'required|date|after:periode_mulai',
            'status'          => 'required|in:aktif,selesai,nonaktif',
        ]);

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'is_active'    => $request->status !== 'nonaktif',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $mahasiswa->update($request->only([
            'nim', 'universitas', 'jurusan', 'divisi',
            'target_proyek', 'periode_mulai', 'periode_selesai', 'status',
        ]));

        return redirect()->route('users.index')
            ->with('success', "Data mahasiswa berhasil diperbarui.");
    }

    /**
     * Nonaktifkan (soft-delete) mahasiswa.
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        $mahasiswa->update(['status' => 'nonaktif']);
        $mahasiswa->user->update(['is_active' => false]);

        return redirect()->route('users.index')
            ->with('success', "Mahasiswa {$mahasiswa->user->nama_lengkap} telah dinonaktifkan.");
    }
}
