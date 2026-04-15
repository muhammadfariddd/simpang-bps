<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProyekController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = Proyek::with(['user', 'milestones'])->latest();

        if ($user->peran === 'mahasiswa') {
            $query->where('user_id', $user->id);
        }

        $proyeks = $query->paginate(10);
        return view('pages.proyek.index', compact('proyeks'));
    }

    public function create()
    {
        return view('pages.proyek.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek'    => 'required|string|max:255',
            'deskripsi'      => 'nullable|string|max:1000',
            'progress_persen'=> 'required|integer|min:0|max:100',
        ]);

        Proyek::create([
            'user_id'         => Auth::id(),
            'nama_proyek'     => $request->nama_proyek,
            'deskripsi'       => $request->deskripsi,
            'progress_persen' => $request->progress_persen,
            'status'          => $request->progress_persen >= 100 ? 'selesai' : 'berjalan',
        ]);

        return redirect()->route('proyek.index')
            ->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function show($id)
    {
        $proyek = Proyek::with(['user', 'milestones'])->findOrFail($id);
        $this->authorize($proyek);
        return view('pages.proyek.show', compact('proyek'));
    }

    public function edit($id)
    {
        $proyek = Proyek::findOrFail($id);
        $this->authorize($proyek);
        return view('pages.proyek.edit', compact('proyek'));
    }

    public function update(Request $request, $id)
    {
        $proyek = Proyek::findOrFail($id);
        $this->authorize($proyek);

        $request->validate([
            'nama_proyek'     => 'required|string|max:255',
            'deskripsi'       => 'nullable|string|max:1000',
            'progress_persen' => 'required|integer|min:0|max:100',
            'file_laporan'    => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = $request->only(['nama_proyek', 'deskripsi', 'progress_persen']);
        $data['status'] = $request->progress_persen >= 100 ? 'selesai' : 'berjalan';

        if ($request->hasFile('file_laporan')) {
            if ($proyek->file_laporan) Storage::disk('public')->delete($proyek->file_laporan);
            $data['file_laporan'] = $request->file('file_laporan')->store('laporan', 'public');
        }

        $proyek->update($data);

        return redirect()->route('proyek.index')
            ->with('success', 'Progress proyek berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $proyek = Proyek::findOrFail($id);
        $this->authorize($proyek);

        if ($proyek->file_laporan) Storage::disk('public')->delete($proyek->file_laporan);
        $proyek->delete();

        return redirect()->route('proyek.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }

    // ─── Milestone ─────────────────────────────────────────────────
    public function storeMilestone(Request $request, $proyekId)
    {
        $proyek = Proyek::findOrFail($proyekId);
        $this->authorize($proyek);

        $request->validate([
            'nama_milestone'  => 'required|string|max:255',
            'deskripsi'       => 'nullable|string|max:500',
            'progress_persen' => 'required|integer|min:0|max:100',
            'target_selesai'  => 'nullable|date',
            'status'          => 'required|in:belum,proses,selesai',
        ]);

        $proyek->milestones()->create($request->only(
            'nama_milestone', 'deskripsi', 'progress_persen', 'target_selesai', 'status'
        ));

        // Update progress induk otomatis
        $avg = $proyek->milestones()->avg('progress_persen');
        $proyek->update(['progress_persen' => (int) $avg]);

        return back()->with('success', 'Milestone berhasil ditambahkan.');
    }

    public function updateMilestone(Request $request, $id)
    {
        $milestone = Milestone::with('proyek')->findOrFail($id);
        $this->authorize($milestone->proyek);

        $request->validate([
            'progress_persen' => 'required|integer|min:0|max:100',
            'status'          => 'required|in:belum,proses,selesai',
        ]);

        $milestone->update($request->only('progress_persen', 'status'));

        // Sinkronisasi progress proyek induk
        $proyek = $milestone->proyek;
        $avg    = $proyek->milestones()->avg('progress_persen');
        $proyek->update(['progress_persen' => (int) $avg]);

        return back()->with('success', 'Milestone diperbarui.');
    }

    private function authorize(Proyek $proyek): void
    {
        $user = Auth::user();
        if ($user->peran === 'mahasiswa' && $proyek->user_id !== $user->id) {
            abort(403);
        }
    }
}
