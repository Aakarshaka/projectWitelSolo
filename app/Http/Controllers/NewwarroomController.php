<?php

namespace App\Http\Controllers;

use App\Models\Newwarroom;
use App\Models\ActionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewwarroomController extends Controller
{
    /**
     * ✅ Helper filter UIC - supaya GS tidak ikut RLEGS, RSO WITEL tidak ikut RSO REGIONAL
     */
    private function filterByUic($query, $uic)
    {
        return $query->where(function ($q) use ($uic) {
            $q->where('uic', $uic)
                ->orWhereRaw("FIND_IN_SET(?, uic)", [$uic])
                ->orWhereRaw("FIND_IN_SET(?, REPLACE(uic, ' ', ''))", [$uic]);
        });
    }

    /**
     * Apply filters to query - PURE dari request saja
     */
    private function applyFilters($query, Request $request)
    {
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $uic = $request->get('uic');
        $search = $request->get('search'); // ✅ Tambahkan search


        if (!empty($bulan) && $bulan !== 'all') {
            $query->byMonth($bulan);
        }

        if (!empty($tahun) && $tahun !== 'all') {
            $query->byYear($tahun);
        }

        if (!empty($uic) && $uic !== 'all') {
            $this->filterByUic($query, $uic); // ✅ FIXED
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('agenda', 'like', "%{$search}%")
                    ->orWhere('uic', 'like', "%{$search}%") // ✅ Tambahkan pencarian UIC
                    ->orWhere('peserta', 'like', "%{$search}%")
                    ->orWhere('pembahasan', 'like', "%{$search}%")
                    ->orWhere('support_needed', 'like', "%{$search}%");
            })
                ->orWhereHas('actionPlans', function ($q) use ($search) {
                    $q->where('action_plan', 'like', "%{$search}%")
                        ->orWhere('update_action_plan', 'like', "%{$search}%")
                        ->orWhere('status_action_plan', 'like', "%{$search}%");
                });
        }
    }

    /**
     * Calculate dashboard statistics
     */
    private function calculateStatistics($warroomData)
    {
        $jumlah_agenda = $warroomData->count();
        $nama_agenda = $warroomData->pluck('agenda')->unique()->values();
        $jumlah_action_plan = $warroomData->sum('jumlah_action_plan');
        $warroomIds = $warroomData->pluck('id');

        // Ringkasan hitung semua status yang relevan
        $status_summary = ActionPlan::whereIn('newwarroom_id', $warroomIds)
            ->selectRaw('status_action_plan, COUNT(*) as total')
            ->whereIn('status_action_plan', ['Open', 'Progress', 'Need Discuss', 'Eskalasi', 'Done'])
            ->groupBy('status_action_plan')
            ->pluck('total', 'status_action_plan')
            ->toArray();

        // Pastikan setiap status muncul (jika nol tetap ada)
        $status_summary = array_merge([
            'Open' => 0,
            'Progress' => 0,
            'Need Discuss' => 0,
            'Eskalasi' => 0,
            'Done' => 0,
        ], $status_summary);

        return compact(
            'jumlah_agenda',
            'nama_agenda',
            'jumlah_action_plan',
            'status_summary'
        );
    }

    /**
     * Validate request data
     */
    private function validateWarroomRequest(Request $request)
    {
        return $request->validate([
            'tgl' => 'nullable|date',
            'agenda' => 'required|string',
            'uic' => 'nullable|string',
            'peserta' => 'nullable|string',
            'pembahasan' => 'nullable|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'required|integer|min:1',
            'supportneeded_id' => 'nullable|integer|exists:supportneeded,id',
        ]);
    }

    /**
     * Validate action plans
     */
    private function validateActionPlans(Request $request, int $jumlah)
    {
        for ($i = 1; $i <= $jumlah; $i++) {
            $request->validate([
                "action_plan_{$i}" => 'required|string',
                "update_action_plan_{$i}" => 'nullable|string',
                "status_action_plan_{$i}" => 'required|in:Open,Progress,Need Discuss,Eskalasi,Done',
            ]);
        }
    }

    /**
     * Tampilkan daftar data warroom dengan filter & search
     */
    public function index(Request $request)
    {
        $query = Newwarroom::with(['actionPlans', 'supportneeded']);

        $this->applyFilters($query, $request);

        $warroomData = $query->orderByRaw('tgl IS NULL')
            ->orderBy('tgl', 'asc')
            ->get();

        $statistics = $this->calculateStatistics($warroomData);

        $tahunList = Newwarroom::selectRaw('YEAR(tgl) as tahun')
            ->whereNotNull('tgl')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $uicList = [
            "TELDA BLORA",
            "TELDA BOYOLALI",
            "TELDA JEPARA",
            "TELDA KLATEN",
            "TELDA KUDUS",
            "TELDA MEA SOLO",
            "TELDA PATI",
            "TELDA PURWODADI",
            "TELDA REMBANG",
            "TELDA SRAGEN",
            "TELDA WONOGIRI",
            "BS",
            "GS",
            "RLEGS",
            "RSO REGIONAL",
            "RSO WITEL",
            "ED",
            "TIF",
            "TSEL",
            "GSD",
            "SSGS",
            "PRQ",
            "RSMES",
            "BPPLP",
            "SSS",
            "LESA V",
            "RWS"
        ];

        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');
        $uic = $request->get('uic');
        $search = $request->get('search'); // ✅ Tambah ini


        $activeFilters = [];
        if (!empty($bulan) && $bulan !== 'all') $activeFilters['bulan'] = $bulan;
        if (!empty($tahun) && $tahun !== 'all') $activeFilters['tahun'] = $tahun;
        if (!empty($uic) && $uic !== 'all') $activeFilters['uic'] = $uic;

        session(['warroom_filters' => $activeFilters]);

        return view('warroom.newwarroom', array_merge(
            compact('warroomData', 'uicList', 'tahunList', 'bulan', 'tahun', 'uic', 'search'),
            $statistics
        ));
    }

    public function create()
    {
        return view('newwarroom.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateWarroomRequest($request);
        $jumlah = (int) $validated['jumlah_action_plan'];
        $this->validateActionPlans($request, $jumlah);

        DB::beginTransaction();

        try {
            $warroom = Newwarroom::create($validated);

            for ($i = 1; $i <= $jumlah; $i++) {
                ActionPlan::create([
                    'newwarroom_id' => $warroom->id,
                    'plan_number' => $i,
                    'action_plan' => $request->input("action_plan_{$i}"),
                    'update_action_plan' => $request->input("update_action_plan_{$i}"),
                    'status_action_plan' => $request->input("status_action_plan_{$i}"),
                ]);
            }

            if (function_exists('log_activity')) {
                log_activity('create', $warroom, 'Menambahkan data Warroom dengan ' . $jumlah . ' action plans');
            }

            DB::commit();

            $sessionFilters = session('warroom_filters', []);

            return redirect()->route('newwarroom.index', $sessionFilters)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Newwarroom $newwarroom)
    {
        $newwarroom->load('actionPlans', 'supportneeded');
        return view('newwarroom.show', compact('newwarroom'));
    }

    public function edit(Newwarroom $newwarroom)
    {
        $newwarroom->load('actionPlans');
        return view('newwarroom.edit', ['data' => $newwarroom]);
    }

    public function update(Request $request, Newwarroom $newwarroom)
    {
        $validated = $this->validateWarroomRequest($request);
        $jumlah = (int) $validated['jumlah_action_plan'];
        $this->validateActionPlans($request, $jumlah);

        DB::beginTransaction();

        try {
            $old = $newwarroom->toArray();
            $newwarroom->update($validated);

            $newwarroom->actionPlans()->delete();
            for ($i = 1; $i <= $jumlah; $i++) {
                ActionPlan::create([
                    'newwarroom_id' => $newwarroom->id,
                    'plan_number' => $i,
                    'action_plan' => $request->input("action_plan_{$i}"),
                    'update_action_plan' => $request->input("update_action_plan_{$i}"),
                    'status_action_plan' => $request->input("status_action_plan_{$i}"),
                ]);
            }

            if (function_exists('log_activity')) {
                log_activity('update', $newwarroom, 'Mengubah data Warroom dengan ' . $jumlah . ' action plans', [
                    'before' => $old,
                    'after' => $newwarroom->toArray(),
                ]);
            }

            DB::commit();

            $sessionFilters = session('warroom_filters', []);

            return redirect()->route('newwarroom.index', $sessionFilters)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Request $request, Newwarroom $newwarroom)
    {
        DB::beginTransaction();

        try {
            if (function_exists('log_activity')) {
                log_activity('delete', $newwarroom, 'Menghapus Warroom: ' . $newwarroom->agenda);
            }

            $newwarroom->delete();
            DB::commit();

            $sessionFilters = session('warroom_filters', []);

            return redirect()->route('newwarroom.index', $sessionFilters)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();

            $sessionFilters = session('warroom_filters', []);

            return redirect()->route('newwarroom.index', $sessionFilters)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getActionPlans(Newwarroom $newwarroom)
    {
        return response()->json($newwarroom->actionPlans()->orderBy('plan_number')->get());
    }

    public function clearFilters()
    {
        session()->forget('warroom_filters');
        return redirect()->route('newwarroom.index');
    }
}
