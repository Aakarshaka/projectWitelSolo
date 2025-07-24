<?php

namespace App\Http\Controllers;

use App\Models\Newwarroom;
use App\Models\ActionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewwarroomController extends Controller
{
    /**
     * Method untuk mendapatkan filter params dari URL/query parameters
     */
    private function getOriginalFilterParams(Request $request)
    {
        $filterParams = [];
        $filters = ['bulan', 'tahun', 'uic', 'search', 'status']; // Tambah status filter

        foreach ($filters as $filter) {
            $value = $request->query($filter);
            if (!empty($value) && $value !== '') {
                $filterParams[$filter] = $value;
            }
        }

        return $filterParams;
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('bulan') && $request->bulan !== '') {
            $query->byMonth($request->bulan);
        }

        if ($request->filled('tahun') && $request->tahun !== '') {
            $query->byYear($request->tahun);
        }

        if ($request->filled('uic') && $request->uic !== '') {
            $query->where('uic', 'like', '%' . $request->uic . '%');
        }

        if ($request->filled('search') && $request->search !== '') {
            $query->search($request->search);
        }

        // Tambahkan filter status action plan
        if ($request->filled('status') && $request->status !== '') {
            $query->whereHas('actionPlans', function ($actionQuery) use ($request) {
                $actionQuery->where('status_action_plan', $request->status);
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

        $jumlah_eskalasi = ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))
            ->where('status_action_plan', 'Eskalasi')
            ->count();

        return compact('jumlah_agenda', 'nama_agenda', 'jumlah_action_plan', 'jumlah_eskalasi');
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
     * Simpan filter parameters ke session untuk mempertahankan filter setelah CRUD operations
     */
    private function preserveFilterParams(Request $request)
    {
        $filterParams = $this->getOriginalFilterParams($request);
        
        // Simpan ke session jika ada filter aktif
        if (!empty($filterParams)) {
            session(['warroom_filters' => $filterParams]);
        }
        
        return $filterParams;
    }

    /**
     * Ambil filter parameters dari session jika tidak ada di request
     */
    private function getPreservedFilters(Request $request)
    {
        $currentFilters = $this->getOriginalFilterParams($request);
        $sessionFilters = session('warroom_filters', []);
        
        // Jika tidak ada filter di request, gunakan dari session
        if (empty($currentFilters) && !empty($sessionFilters)) {
            return $sessionFilters;
        }
        
        return $currentFilters;
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

        // Tahun Dinamis dari data tgl
        $tahunList = Newwarroom::selectRaw('YEAR(tgl) as tahun')
            ->whereNotNull('tgl')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // UIC List
        $uicList = [
            "TELDA BLORA", "TELDA BOYOLALI", "TELDA JEPARA", "TELDA KLATEN", 
            "TELDA KUDUS", "TELDA MEA SOLO", "TELDA PATI", "TELDA PURWODADI", 
            "TELDA REMBANG", "TELDA SRAGEN", "TELDA WONOGIRI", "BS", "GS", 
            "RLEGS", "RSO REGIONAL", "RSO WITEL", "ED", "TIF", "TSEL", 
            "GSD", "SSGS", "PRQ", "RSMES", "BPPLP", "SSS"
        ];

        // Status List untuk filter
        $statusList = ['Open', 'Progress', 'Need Discuss', 'Eskalasi', 'Done'];

        // Get current filter values dengan preserved filters
        $preservedFilters = $this->getPreservedFilters($request);
        
        $bulan = $request->filled('bulan') && $request->bulan !== '' ? $request->bulan : ($preservedFilters['bulan'] ?? null);
        $tahun = $request->filled('tahun') && $request->tahun !== '' ? $request->tahun : ($preservedFilters['tahun'] ?? null);
        $uic = $request->filled('uic') && $request->uic !== '' ? $request->uic : ($preservedFilters['uic'] ?? null);
        $search = $request->filled('search') && $request->search !== '' ? $request->search : ($preservedFilters['search'] ?? null);
        $status = $request->filled('status') && $request->status !== '' ? $request->status : ($preservedFilters['status'] ?? null);

        // Simpan filter aktif ke session
        $this->preserveFilterParams($request);

        return view('warroom.newwarroom', array_merge(
            compact('warroomData', 'bulan', 'tahun', 'uic', 'uicList', 'tahunList', 'search', 'status', 'statusList'),
            $statistics
        ));
    }

    /**
     * Tampilkan form tambah data
     */
    public function create()
    {
        return view('newwarroom.create');
    }

    /**
     * Simpan data baru dengan action plans
     */
    public function store(Request $request)
    {
        $validated = $this->validateWarroomRequest($request);
        $jumlah = (int) $validated['jumlah_action_plan'];
        $this->validateActionPlans($request, $jumlah);

        // Preserve filter parameters sebelum operasi
        $filterParams = $this->preserveFilterParams($request);

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

            // Gunakan filter yang telah dipreserve
            $redirectFilters = !empty($filterParams) ? $filterParams : session('warroom_filters', []);
            
            return redirect()->route('newwarroom.index', $redirectFilters)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan detail warroom
     */
    public function show(Newwarroom $newwarroom)
    {
        $newwarroom->load('actionPlans', 'supportneeded');
        return view('newwarroom.show', compact('newwarroom'));
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Newwarroom $newwarroom)
    {
        $newwarroom->load('actionPlans');
        return view('newwarroom.edit', ['data' => $newwarroom]);
    }

    /**
     * Update data warroom & action plans
     */
    public function update(Request $request, Newwarroom $newwarroom)
    {
        $validated = $this->validateWarroomRequest($request);
        $jumlah = (int) $validated['jumlah_action_plan'];
        $this->validateActionPlans($request, $jumlah);

        // Preserve filter parameters SEBELUM update
        $filterParams = $this->preserveFilterParams($request);

        DB::beginTransaction();

        try {
            $old = $newwarroom->toArray();
            $newwarroom->update($validated);

            // Hapus & ganti action plans
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

            // PENTING: Gunakan filter yang telah dipreserve, bukan yang baru dari request
            $redirectFilters = !empty($filterParams) ? $filterParams : session('warroom_filters', []);
            
            return redirect()->route('newwarroom.index', $redirectFilters)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus data warroom & action plans
     */
    public function destroy(Request $request, Newwarroom $newwarroom)
    {
        // Preserve filter parameters sebelum operasi
        $filterParams = $this->preserveFilterParams($request);

        DB::beginTransaction();

        try {
            if (function_exists('log_activity')) {
                log_activity('delete', $newwarroom, 'Menghapus Warroom: ' . $newwarroom->agenda);
            }
            
            $newwarroom->delete();
            DB::commit();

            // Gunakan filter yang telah dipreserve
            $redirectFilters = !empty($filterParams) ? $filterParams : session('warroom_filters', []);
            
            return redirect()->route('newwarroom.index', $redirectFilters)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();

            // Gunakan filter yang telah dipreserve
            $redirectFilters = !empty($filterParams) ? $filterParams : session('warroom_filters', []);
            
            return redirect()->route('newwarroom.index', $redirectFilters)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API untuk ambil action plans (untuk script edit)
     */
    public function getActionPlans(Newwarroom $newwarroom)
    {
        return response()->json($newwarroom->actionPlans()->orderBy('plan_number')->get());
    }

    /**
     * Method untuk clear filter session (opsional)
     */
    public function clearFilters()
    {
        session()->forget('warroom_filters');
        return redirect()->route('newwarroom.index');
    }
}