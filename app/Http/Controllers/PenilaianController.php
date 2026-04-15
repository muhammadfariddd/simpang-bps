<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    /**
     * Daftar penilaian (admin: semua; mahasiswa: milik sendiri).
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->peran === 'admin') {
            $mahasiswas = Mahasiswa::with(['user', 'penilaian'])->get();
            return view('pages.penilaian.index', compact('mahasiswas'));
        }

        $penilaian = Penilaian::where('user_id', $user->id)->first();
        return view('pages.penilaian.show', compact('penilaian'));
    }

    /**
     * Form penilaian untuk mahasiswa tertentu (admin only).
     */
    public function create($mahasiswaId)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($mahasiswaId);
        return view('pages.penilaian.create', compact('mahasiswa'));
    }

    /**
     * Simpan penilaian.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'kedisiplinan'   => 'required|integer|min:0|max:100',
            'kualitas_kerja' => 'required|integer|min:0|max:100',
            'inisiatif'      => 'required|integer|min:0|max:100',
            'kerjasama'      => 'required|integer|min:0|max:100',
            'komunikasi'     => 'required|integer|min:0|max:100',
            'catatan'        => 'nullable|string|max:1000',
        ]);

        $nilai = ($request->kedisiplinan + $request->kualitas_kerja +
                  $request->inisiatif   + $request->kerjasama    +
                  $request->komunikasi) / 5;

        $penilaian = Penilaian::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'admin_id'       => Auth::id(),
                'kedisiplinan'   => $request->kedisiplinan,
                'kualitas_kerja' => $request->kualitas_kerja,
                'inisiatif'      => $request->inisiatif,
                'kerjasama'      => $request->kerjasama,
                'komunikasi'     => $request->komunikasi,
                'catatan'        => $request->catatan,
                'nilai_akhir'    => round($nilai, 2),
            ]
        );

        // Jika status mahasiswa belum selesai, pertimbangkan update
        $mahasiswa = Mahasiswa::where('user_id', $request->user_id)->first();
        if ($mahasiswa && $request->boolean('tandai_selesai')) {
            $mahasiswa->update([
                'status'      => 'selesai',
                'nilai_akhir' => $penilaian->nilai_akhir,
            ]);
        }

        return redirect()->route('penilaian.index')
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Detail penilaian.
     */
    public function show($id)
    {
        $penilaian = Penilaian::with(['mahasiswa', 'admin'])->findOrFail($id);
        return view('pages.penilaian.show', compact('penilaian'));
    }
}
