<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->peran === 'admin') {
            $pengumumans = Pengumuman::with('admin')->latest()->paginate(10);
            return view('pages.pengumuman.index', compact('pengumumans'));
        }

        // Mahasiswa: tampilkan pengumuman yang relevan
        $pengumumans = Pengumuman::whereIn('target', ['semua', 'mahasiswa'])
            ->orWhere('target', (string) $user->id)
            ->with('admin')
            ->latest()
            ->paginate(10);

        return view('pages.pengumuman.index', compact('pengumumans'));
    }

    public function create()
    {
        return view('pages.pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'isi'       => 'required|string',
            'target'    => 'required|string',
            'is_pinned' => 'boolean',
        ]);

        Pengumuman::create([
            'admin_id'  => Auth::id(),
            'judul'     => $request->judul,
            'isi'       => $request->isi,
            'target'    => $request->target,
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dikirim.');
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
