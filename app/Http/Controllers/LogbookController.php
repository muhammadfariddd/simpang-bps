<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Logbook::with('user')->latest('tanggal');

        // Mahasiswa hanya lihat milik sendiri
        if ($user->peran === 'mahasiswa') {
            $query->where('user_id', $user->id);
        }

        // Filter status (admin)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logbooks  = $query->paginate(15)->withQueryString();
        $kategoriList = Logbook::kategoriList();

        return view('pages.logbook.index', compact('logbooks', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = Logbook::kategoriList();
        return view('pages.logbook.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'tanggal'            => 'required|date|before_or_equal:today',
            'deskripsi_kegiatan' => 'required|string|max:2000',
            'kategori'           => 'required|in:' . implode(',', Logbook::kategoriList()),
            'file_bukti'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'link_bukti'         => 'nullable|url|max:500',
        ], [
            'tanggal.before_or_equal' => 'Tanggal logbook tidak boleh di masa depan.',
            'file_bukti.max'          => 'Ukuran file maksimal 5 MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_bukti')) {
            $filePath = $request->file('file_bukti')->store('logbook', 'public');
        }

        Logbook::create([
            'user_id'            => $user->id,
            'tanggal'            => $request->tanggal,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'kategori'           => $request->kategori,
            'file_bukti'         => $filePath,
            'link_bukti'         => $request->link_bukti,
            'status'             => 'pending',
        ]);

        return redirect()->route('logbook.index')
            ->with('success', 'Logbook berhasil disimpan dan menunggu validasi admin.');
    }

    public function show($id)
    {
        $logbook = Logbook::with(['user', 'validator'])->findOrFail($id);
        $this->authorizeView($logbook);
        return view('pages.logbook.show', compact('logbook'));
    }

    public function edit($id)
    {
        $logbook = Logbook::findOrFail($id);
        $this->authorizeEdit($logbook);
        $kategoriList = Logbook::kategoriList();
        return view('pages.logbook.edit', compact('logbook', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);
        $this->authorizeEdit($logbook);

        $request->validate([
            'tanggal'            => 'required|date|before_or_equal:today',
            'deskripsi_kegiatan' => 'required|string|max:2000',
            'kategori'           => 'required|in:' . implode(',', Logbook::kategoriList()),
            'file_bukti'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'link_bukti'         => 'nullable|url|max:500',
        ]);

        $data = $request->only(['tanggal', 'deskripsi_kegiatan', 'kategori', 'link_bukti']);
        $data['status'] = 'pending'; // reset ke pending setelah edit

        if ($request->hasFile('file_bukti')) {
            if ($logbook->file_bukti) Storage::disk('public')->delete($logbook->file_bukti);
            $data['file_bukti'] = $request->file('file_bukti')->store('logbook', 'public');
        }

        $logbook->update($data);

        return redirect()->route('logbook.index')
            ->with('success', 'Logbook berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $logbook = Logbook::findOrFail($id);
        $this->authorizeEdit($logbook);

        if ($logbook->file_bukti) Storage::disk('public')->delete($logbook->file_bukti);
        $logbook->delete();

        return redirect()->route('logbook.index')
            ->with('success', 'Logbook berhasil dihapus.');
    }

    /**
     * Admin: validasi logbook (setujui / revisi).
     */
    public function validasi(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);

        $request->validate([
            'aksi'           => 'required|in:disetujui,revisi',
            'komentar_admin' => 'nullable|string|max:500',
        ]);

        $logbook->update([
            'status'          => $request->aksi,
            'komentar_admin'  => $request->komentar_admin,
            'divalidasi_oleh' => Auth::id(),
            'divalidasi_pada' => now(),
        ]);

        $label = $request->aksi === 'disetujui' ? 'disetujui' : 'diminta revisi';
        return back()->with('success', "Logbook berhasil {$label}.");
    }

    // ─── Helpers ───────────────────────────────────────────────────
    private function authorizeView(Logbook $logbook): void
    {
        $user = Auth::user();
        if ($user->peran === 'mahasiswa' && $logbook->user_id !== $user->id) {
            abort(403);
        }
    }

    private function authorizeEdit(Logbook $logbook): void
    {
        $user = Auth::user();
        if ($user->peran === 'mahasiswa') {
            if ($logbook->user_id !== $user->id || $logbook->status === 'disetujui') {
                abort(403, 'Logbook yang sudah disetujui tidak dapat diedit.');
            }
        }
    }
}
