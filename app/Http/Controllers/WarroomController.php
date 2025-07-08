<?php

namespace App\Http\Controllers;

use App\Models\Warroom;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WarroomController extends Controller
{
    public function index(Request $request)
    {
        $bulanFilter = $request->input('bulan') ?? Carbon::now()->format('m');
        $tahunFilter = $request->input('tahun') ?? Carbon::now()->format('Y');

        $activities = Warroom::whereMonth('tgl', $bulanFilter)
            ->whereYear('tgl', $tahunFilter)
            ->orderBy('tgl', 'asc')
            ->get();

        $jumlahAgenda     = $activities->pluck('agenda')->unique()->count();
        $jumlahActionPlan = $activities->sum('jumlah_action_plan');
        $jumlahEskalasi   = $activities->where('status_action_plan', 'like', '%eskalasi%')->count();

        $daftarBulan = collect(range(1, 12))->map(function ($m) {
            return Carbon::create()->month($m)->format('F');
        });

        return view('warroom', compact(
            'activities',
            'jumlahAgenda',
            'jumlahActionPlan',
            'jumlahEskalasi',
            'bulanFilter',
            'tahunFilter',
            'daftarBulan'
        ));
    }

    public function create()
    {
        return view('warroom.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        Warroom::create($validated);

        return redirect()->route('warroom.index')->with('success', 'Data Warroom berhasil ditambahkan.');
    }

    public function show(Warroom $warroom)
    {
        return view('warroom.show', compact('warroom'));
    }

    public function edit(Warroom $warroom)
    {
        return view('warroom.edit', compact('warroom'));
    }

    public function update(Request $request, Warroom $warroom)
    {
        $validated = $this->validateInput($request);
        $warroom->update($validated);

        return redirect()->route('warroom.index')->with('success', 'Data Warroom berhasil diperbarui.');
    }

    public function destroy(Warroom $warroom)
    {
        $warroom->delete();
        return redirect()->route('warroom.index')->with('success', 'Data Warroom berhasil dihapus.');
    }

    /**
     * Helper untuk validasi input
     */
    private function validateInput(Request $request)
    {
        return $request->validate([
            'tgl'                => 'nullable|date',
            'agenda'             => 'required|string|max:255',
            'peserta'            => 'nullable|string|max:255',
            'pembahasan'         => 'nullable|string',
            'action_plan'        => 'nullable|string',
            'support_needed'     => 'nullable|string',
            'info_kompetitor'    => 'nullable|string',
            'jumlah_action_plan' => 'nullable|integer',
            'update_action_plan' => 'nullable|string',
            'status_action_plan' => 'nullable|string|max:255',
        ]);
    }
}
