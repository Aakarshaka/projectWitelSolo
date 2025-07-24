<?php

namespace App\Http\Controllers;

use App\Models\Newwarroom;
use App\Models\ActionPlan;
use App\Models\Supportneeded;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class NewwarroomController extends Controller
{
    /**
     * ✅ Method untuk mendapatkan filter params dari URL/query parameters
     * Digunakan untuk mempertahankan filter state setelah operasi CRUD
     */
    private function getOriginalFilterParams(Request $request)
    {
        $filterParams = [];
        $filters = ['bulan', 'tahun', 'uic', 'search'];

        // ✅ HANYA ambil dari query parameters (URL) untuk menghindari "nyangkut"
        foreach ($filters as $filter) {
            $value = $request->query($filter);
            // Pastikan value tidak kosong dan bukan string kosong
            if (!empty($value) && $value !== '') {
                $filterParams[$filter] = $value;
            }
        }

        return $filterParams;
    }

    /**
     * ✅ Method untuk clear filter yang bermasalah
     */
    private function clearFilterSession()
    {
        $filters = ['bulan', 'tahun', 'uic', 'search'];
        foreach ($filters as $filter) {
            if (session()->has("filter_{$filter}")) {
                session()->forget("filter_{$filter}");
            }
        }
    }

    /**
     * ✅ Apply filters to query (konsisten dengan SupportneededController)
     */
    private function applyFilters($query, Request $request)
    {
        // Filter berdasarkan bulan
        if ($request->filled('bulan') && $request->bulan !== '') {
            $query->byMonth($request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun') && $request->tahun !== '') {
            $query->byYear($request->tahun);
        }

        // Filter berdasarkan UIC
        if ($request->filled('uic') && $request->uic !== '') {
            $query->where('uic', 'like', '%' . $request->uic . '%');
        }

        // Search
        if ($request->filled('search') && $request->search !== '') {
            $query->search($request->search);
        }
    }

    /**
     * ✅ Calculate dashboard statistics (konsisten dengan SupportneededController)
     */
    private function calculateStatistics($warroomData)
    {
        $jumlah_agenda = $warroomData->count();
        $nama_agenda = $warroomData->pluck('agenda')->unique()->values();
        $jumlah_action_plan = $warroomData->sum('jumlah_action_plan');

        $jumlah_eskalasi = ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))
            ->where('status_action_plan', 'Eskalasi')
            ->count();

        $action_plan_stats = [
            'total' => ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))->count(),
            'done' => ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))->where('status_action_plan', 'Done')->count(),
            'progress' => ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))->where('status_action_plan', 'Progress')->count(),
            'open' => ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))->where('status_action_plan', 'Open')->count(),
        ];

        return compact('jumlah_agenda', 'nama_agenda', 'jumlah_action_plan', 'jumlah_eskalasi', 'action_plan_stats');
    }

    /**
     * ✅ Tampilkan daftar data warroom dengan filter & search
     */
    public function index(Request $request)
    {
        // Clear filter session jika ada masalah
        $this->clearFilterSession();

        $query = Newwarroom::with(['actionPlans', 'supportneeded']);

        // Apply filters using consistent method
        $this->applyFilters($query, $request);

        $warroomData = $query->orderByRaw('tgl IS NULL')
            ->orderBy('tgl', 'asc')
            ->get();

        // Calculate statistics using consistent method
        $statistics = $this->calculateStatistics($warroomData);

        // ✅ Tahun Dinamis dari data tgl
        $tahunList = Newwarroom::selectRaw('YEAR(tgl) as tahun')
            ->whereNotNull('tgl')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // ✅ UIC Statis
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
            "SSS"
        ];

        // ✅ Get current filter values - pastikan tidak ada nilai kosong
        $bulan = $request->filled('bulan') && $request->bulan !== '' ? $request->bulan : null;
        $tahun = $request->filled('tahun') && $request->tahun !== '' ? $request->tahun : null;
        $uic = $request->filled('uic') && $request->uic !== '' ? $request->uic : null;
        $search = $request->filled('search') && $request->search !== '' ? $request->search : null;

        return view('warroom.newwarroom', array_merge(
            compact('warroomData', 'bulan', 'tahun', 'uic', 'uicList', 'tahunList', 'search'),
            $statistics
        ));
    }

    /**
     * ✅ Validate request data (konsisten dengan SupportneededController)
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
     * ✅ Validate action plans (konsisten dengan SupportneededController)
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
     * ✅ Tampilkan form tambah data
     */
    public function create()
    {
        return view('newwarroom.create');
    }

    /**
     * ✅ Simpan data baru dengan action plans (dengan filter preservation)
     */
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

            log_activity('create', $warroom, 'Menambahkan data Warroom dengan ' . $jumlah . ' action plans');

            DB::commit();

            // ✅ PERBAIKAN: Gunakan getOriginalFilterParams() yang konsisten
            $filterParams = $this->getOriginalFilterParams($request);
            return redirect()->route('newwarroom.index', $filterParams)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ✅ Tampilkan detail warroom
     */
    public function show(Newwarroom $newwarroom)
    {
        $newwarroom->load('actionPlans', 'supportneeded');
        return view('newwarroom.show', compact('newwarroom'));
    }

    /**
     * ✅ Tampilkan form edit
     */
    public function edit(Newwarroom $newwarroom)
    {
        $newwarroom->load('actionPlans');
        return view('newwarroom.edit', ['data' => $newwarroom]);
    }

    /**
     * ✅ Update data warroom & action plans (dengan filter preservation)
     */
    public function update(Request $request, Newwarroom $newwarroom)
    {
        $validated = $this->validateWarroomRequest($request);
        $jumlah = (int) $validated['jumlah_action_plan'];
        $this->validateActionPlans($request, $jumlah);

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

            log_activity('update', $newwarroom, 'Mengubah data Warroom dengan ' . $jumlah . ' action plans', [
                'before' => $old,
                'after' => $newwarroom->toArray(),
            ]);

            DB::commit();

            // ✅ PERBAIKAN: Gunakan getOriginalFilterParams() yang konsisten
            $filterParams = $this->getOriginalFilterParams($request);
            return redirect()->route('newwarroom.index', $filterParams)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ✅ Hapus data warroom & action plans (dengan filter preservation)
     */
    public function destroy(Request $request, Newwarroom $newwarroom)
    {
        DB::beginTransaction();

        try {
            log_activity('delete', $newwarroom, 'Menghapus Warroom: ' . $newwarroom->agenda);
            $newwarroom->delete();
            DB::commit();

            // ✅ PERBAIKAN: Gunakan getOriginalFilterParams() yang konsisten
            $filterParams = $this->getOriginalFilterParams($request);
            return redirect()->route('newwarroom.index', $filterParams)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();

            $filterParams = $this->getOriginalFilterParams($request);
            return redirect()->route('newwarroom.index', $filterParams)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * ✅ API untuk ambil action plans (untuk script edit)
     */
    public function getActionPlans(Newwarroom $newwarroom)
    {
        return response()->json($newwarroom->actionPlans()->orderBy('plan_number')->get());
    }

    /**
     * ✅ Update status action plan individual (Ajax)
     */
    public function updateActionPlanStatus(Request $request, ActionPlan $actionPlan)
    {
        $validated = $request->validate([
            'status_action_plan' => 'required|in:Open,Progress,Need Discuss,Eskalasi,Done',
            'update_action_plan' => 'nullable|string',
        ]);

        $actionPlan->update($validated);
        return response()->json(['success' => true, 'message' => 'Status action plan berhasil diperbarui']);
    }

    /**
     * ✅ Get detail data for popup (API endpoint) - konsisten dengan SupportneededController
     */
    public function getDetail(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        $uic = $request->query('uic');
        $agenda = $request->query('agenda');

        $query = Newwarroom::with(['actionPlans', 'supportneeded']);

        if ($bulan) {
            $query->byMonth($bulan);
        }

        if ($tahun) {
            $query->byYear($tahun);
        }

        if ($uic) {
            $query->where('uic', 'like', '%' . $uic . '%');
        }

        if ($agenda) {
            $query->where('agenda', 'like', '%' . $agenda . '%');
        }

        $data = $query->get();
        return response()->json($data);
    }

    /**
     * ✅ Clear all filters - method untuk reset filter
     */
    public function clearFilters(Request $request)
    {
        // Clear session filters if any
        $this->clearFilterSession();
        
        // Redirect to index without any query parameters
        return redirect()->route('newwarroom.index')->with('success', 'Filter berhasil direset.');
    }
}
