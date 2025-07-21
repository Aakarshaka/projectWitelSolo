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
        $search = $request->input('search');

        $query = Newwarroom::query();

        if (!empty($bulan)) {
            $query->whereMonth('tgl', $bulan);
        }

        if (!empty($tahun)) {
            $query->whereYear('tgl', $tahun);
        }

        // Tambahkan pencarian global di semua kolom utama
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

        // Tambahkan pengurutan berdasarkan tgl (tanggal)
        // Data dengan tgl null akan ditampilkan di akhir
        $warroomData = $query->orderByRaw('tgl IS NULL')
                            ->orderBy('tgl', 'asc')
                            ->get();

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
            'search'
        ));
    }

    /**
     * Sinkronisasi data dari supportneeded yang status-nya 'Action'.
     * Method ini diperbaiki untuk menghindari duplikasi dan memastikan supportneeded_id diset
     */
    public function syncFromSupportneeded(): RedirectResponse
    {
        $data = Supportneeded::where('status', 'Action')
                            ->orderByRaw('start_date IS NULL')
                            ->orderBy('start_date', 'asc')
                            ->get();

        foreach ($data as $item) {
            // Gunakan updateOrCreate untuk menghindari duplikasi
            Newwarroom::updateOrCreate(
                ['supportneeded_id' => $item->id], // Cari berdasarkan supportneeded_id
                [
                    'tgl' => $item->start_date,
                    'agenda' => $item->agenda,
                    'uic' => $item->uic,
                    'support_needed' => $item->notes_to_follow_up,
                    'peserta' => null, // Set default jika diperlukan
                    'pembahasan' => null,
                    'action_plan' => null,
                    'info_kompetitor' => null,
                    'jumlah_action_plan' => 0,
                    'update_action_plan' => null,
                    'status_action_plan' => $item->status,
                    'supportneeded_id' => $item->id, // Pastikan supportneeded_id diset
                ]
            );
        }

        // Hapus data warroom yang supportneeded-nya sudah tidak berstatus 'Action'
        $actionIds = Supportneeded::where('status', 'Action')->pluck('id');
        Newwarroom::whereNotNull('supportneeded_id')
            ->whereNotIn('supportneeded_id', $actionIds)
            ->delete();

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil disinkronkan.');
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
            'supportneeded_id' => 'nullable|integer|exists:supportneeded,id', // Tambahkan validasi
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
            'supportneeded_id' => 'nullable|integer|exists:supportneeded,id', // Tambahkan validasi
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