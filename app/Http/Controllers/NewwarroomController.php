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
    public function index()
    {
        $warroomData = Newwarroom::all();
        return view('warroom.newwarroom', compact('warroomData'));
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
                    'tgl'     => $item->start_date,
                    'agenda'  => $item->agenda,
                    'uic'     => $item->uic,
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

        Newwarroom::create($validated);

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

        $newwarroom->update($validated);

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy(Newwarroom $newwarroom)
    {
        $newwarroom->delete();

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil dihapus.');
    }
}
