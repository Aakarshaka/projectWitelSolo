<?php

namespace App\Http\Controllers;

use App\Models\Newwarroom;
use App\Models\Supportneeded;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class NewwarroomController extends Controller
{
    /**
     * Tampilkan daftar data warroom.
     */
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $search = $request->input('search'); // << Tambahkan ini

        $query = Newwarroom::query();

        if (!empty($bulan)) {
            $query->whereMonth('tgl', $bulan);
        }

        if (!empty($tahun)) {
            $query->whereYear('tgl', $tahun);
        }

        // 🔍 Tambahkan pencarian global di semua kolom utama
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('agenda', 'like', "%$search%")
                    ->orWhere('uic', 'like', "%$search%")
                    ->orWhere('peserta', 'like', "%$search%")
                    ->orWhere('pembahasan', 'like', "%$search%")
                    ->orWhere('action_plan', 'like', "%$search%")
                    ->orWhere('support_needed', 'like', "%$search%")
                    ->orWhere('info_kompetitor', 'like', "%$search%")
                    ->orWhere('jumlah_action_plan', 'like', "%$search%")
                    ->orWhere('update_action_plan', 'like', "%$search%")
                    ->orWhere('status_action_plan', 'like', "%$search%");
            });
        }

        $warroomData = $query->get();

        $jumlah_agenda = $warroomData->count();
        $nama_agenda = $warroomData->pluck('agenda')->unique()->values();
        $jumlah_action_plan = $warroomData->sum('jumlah_action_plan');
        $jumlah_eskalasi = $warroomData->where('status_action_plan', 'Eskalasi')->count();

        return view('warroom.newwarroom', compact(
            'warroomData',
            'jumlah_agenda',
            'nama_agenda',
            'jumlah_action_plan',
            'jumlah_eskalasi',
            'bulan',
            'tahun',
            'search' // ⬅ penting dikirim ke blade biar input tetap terisi
        ));
    }

    /**
     * Sinkronisasi data dari supportneeded yang status-nya 'Action'.
     */
    public function syncFromSupportneeded(): RedirectResponse
    {
        $data = Supportneeded::where('status', 'Action')->get();

        foreach ($data as $item) {
            $exists = Newwarroom::where('tgl', $item->start_date)
                ->where('agenda', $item->agenda)
                ->where('uic', $item->uic)
                ->exists();

            if (!$exists) {
                Newwarroom::create([
                    'tgl' => $item->start_date,
                    'agenda' => $item->agenda,
                    'uic' => $item->uic,
                    // Kolom lain dibiarkan null
                ]);
            }
        }

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil disalin dari Supportneeded.');
    }

    /**
     * Tampilkan form tambah data (kalau pakai modal, bisa kosong).
     */
    public function create()
    {
        return view('newwarroom.create');
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'nullable|date',
            'agenda' => 'nullable|string',
            'uic' => 'nullable|string',
            'peserta' => 'nullable|string',
            'pembahasan' => 'nullable|string',
            'action_plan' => 'nullable|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'nullable|integer',
            'update_action_plan' => 'nullable|string',
            'status_action_plan' => 'nullable|string',
        ]);

        $war = Newwarroom::create($validated);
        log_activity('create', $war, 'Menambahkan data Warroom');

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail (tidak digunakan).
     */
    public function show(Newwarroom $newwarroom)
    {
        //
    }

    /**
     * Tampilkan form edit.
     */
    public function edit(Newwarroom $newwarroom)
    {
        return view('newwarroom.edit', ['data' => $newwarroom]);
    }

    /**
     * Update data.
     */
    public function update(Request $request, Newwarroom $newwarroom)
    {
        $validated = $request->validate([
            'tgl' => 'nullable|date',
            'agenda' => 'nullable|string',
            'uic' => 'nullable|string',
            'peserta' => 'nullable|string',
            'pembahasan' => 'nullable|string',
            'action_plan' => 'nullable|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'nullable|integer',
            'update_action_plan' => 'nullable|string',
            'status_action_plan' => 'nullable|string',
        ]);

        $old = $newwarroom->toArray();
        $newwarroom->update($validated);

        log_activity('update', $newwarroom, 'Mengubah data Warroom', [
            'before' => $old,
            'after' => $newwarroom->toArray(),
        ]);

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy(Newwarroom $newwarroom)
    {
        log_activity('delete', $newwarroom, 'Menghapus data Warroom: ' . $newwarroom->agenda);
        $newwarroom->delete();

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil dihapus.');
    }
}
