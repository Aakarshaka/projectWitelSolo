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
    private function getFilterParams(Request $request)
    {
        return $request->only(['bulan', 'tahun', 'uic', 'search']);
    }

    /**
     * ✅ Tampilkan daftar data warroom dengan filter & search
     */
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $uic = $request->input('uic');
        $search = $request->input('search');

        $query = Newwarroom::with(['actionPlans', 'supportneeded']);

        if (!empty($bulan)) {
            $query->byMonth($bulan);
        }

        if (!empty($tahun)) {
            $query->byYear($tahun);
        }

        if (!empty($uic)) {
            $query->where('uic', 'like', '%' . $uic . '%');
        }

        if (!empty($search)) {
            $query->search($search);
        }

        $warroomData = $query->orderByRaw('tgl IS NULL')
            ->orderBy('tgl', 'asc')
            ->get();

        // Statistik
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

        return view('warroom.newwarroom', compact(
            'warroomData',
            'jumlah_agenda',
            'nama_agenda',
            'jumlah_action_plan',
            'jumlah_eskalasi',
            'action_plan_stats',
            'bulan',
            'tahun',
            'uic',
            'uicList',
            'tahunList',
            'search'
        ));
    }

    /**
     * ✅ Tampilkan form tambah data
     */
    public function create()
    {
        return view('newwarroom.create');
    }

    /**
     * ✅ Simpan data baru dengan action plans
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'nullable|date',
            'agenda' => 'required|string',
            'uic' => 'nullable|string',
            'peserta' => 'nullable|string',
            'pembahasan' => 'nullable|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'required|integer|min:1|max:10',
            'supportneeded_id' => 'nullable|integer|exists:supportneeded,id',
        ]);

        $jumlah = (int) $validated['jumlah_action_plan'];
        for ($i = 1; $i <= $jumlah; $i++) {
            $request->validate([
                "action_plan_{$i}" => 'required|string',
                "update_action_plan_{$i}" => 'nullable|string',
                "status_action_plan_{$i}" => 'required|in:Open,Progress,Need Discuss,Eskalasi,Done',
            ]);
        }

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
            return redirect()->route('newwarroom.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
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

            // ✅ PERUBAHAN: Preserve filter parameters
            $filterParams = $this->getFilterParams($request);
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
     * ✅ Update data warroom & action plans (data action plans tidak reset)
     */
    public function update(Request $request, Newwarroom $newwarroom)
    {
        $validated = $request->validate([
            'tgl' => 'nullable|date',
            'agenda' => 'required|string',
            'uic' => 'nullable|string',
            'peserta' => 'nullable|string',
            'pembahasan' => 'nullable|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'required|integer|min:1|max:10',
            'supportneeded_id' => 'nullable|integer|exists:supportneeded,id',
        ]);

        $jumlah = (int) $validated['jumlah_action_plan'];
        for ($i = 1; $i <= $jumlah; $i++) {
            $request->validate([
                "action_plan_{$i}" => 'required|string',
                "update_action_plan_{$i}" => 'nullable|string',
                "status_action_plan_{$i}" => 'required|in:Open,Progress,Need Discuss,Eskalasi,Done',
            ]);
        }

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
            return redirect()->route('newwarroom.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }

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

            // ✅ PERUBAHAN: Preserve filter parameters
            $filterParams = $this->getFilterParams($request);
            return redirect()->route('newwarroom.index', $filterParams)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * ✅ Hapus data warroom & action plans
     */
    public function destroy(Newwarroom $newwarroom)
    {
        DB::beginTransaction();

        try {
            log_activity('delete', $newwarroom, 'Menghapus Warroom: ' . $newwarroom->agenda);
            $newwarroom->delete();
            DB::commit();

            // ✅ PERUBAHAN: Preserve filter parameters
            $filterParams = $this->getFilterParams($request);
            return redirect()->route('newwarroom.index', $filterParams)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();

            // ✅ PERUBAHAN: Preserve filter parameters untuk error juga
            $filterParams = $this->getFilterParams($request);
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
}
