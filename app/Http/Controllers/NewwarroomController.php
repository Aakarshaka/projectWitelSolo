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
     * Tampilkan daftar data warroom.
     */
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $search = $request->input('search');

        $query = Newwarroom::with(['actionPlans', 'supportneeded']);

        if (!empty($bulan)) {
            $query->byMonth($bulan);
        }

        if (!empty($tahun)) {
            $query->byYear($tahun);
        }

        if (!empty($search)) {
            $query->search($search);
        }

        // Tambahkan pengurutan berdasarkan tgl (tanggal)
        $warroomData = $query->orderByRaw('tgl IS NULL')
            ->orderBy('tgl', 'asc')
            ->get();

        // Statistik
        $jumlah_agenda = $warroomData->count();
        $nama_agenda = $warroomData->pluck('agenda')->unique()->values();
        $jumlah_action_plan = $warroomData->sum('jumlah_action_plan');

        // Hitung eskalasi dari action plans
        $jumlah_eskalasi = ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))
            ->where('status_action_plan', 'Eskalasi')
            ->count();

        // Statistik tambahan untuk action plans
        $action_plan_stats = [
            'total' => ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))->count(),
            'done' => ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))->where('status_action_plan', 'Done')->count(),
            'progress' => ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))->where('status_action_plan', 'Progress')->count(),
            'open' => ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))->where('status_action_plan', 'Open')->count(),
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
            'search'
        ));
    }

    /**
     * Sinkronisasi data dari supportneeded yang status-nya 'Action'.
     */
    public function syncFromSupportneeded(): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $data = Supportneeded::where('status', 'Action')
                ->orderByRaw('start_date IS NULL')
                ->orderBy('start_date', 'asc')
                ->get();

            foreach ($data as $item) {
                $warroom = Newwarroom::updateOrCreate(
                    ['supportneeded_id' => $item->id],
                    [
                        'tgl' => $item->start_date,
                        'agenda' => $item->agenda,
                        'uic' => $item->uic,
                        'support_needed' => $item->notes_to_follow_up,
                        'peserta' => null,
                        'pembahasan' => null,
                        'info_kompetitor' => null,
                        'jumlah_action_plan' => 1, // Set default 1 action plan
                        'supportneeded_id' => $item->id,
                    ]
                );

                // Buat action plan default jika belum ada
                if ($warroom->actionPlans()->count() == 0) {
                    ActionPlan::create([
                        'newwarroom_id' => $warroom->id,
                        'plan_number' => 1,
                        'action_plan' => $item->notes_to_follow_up ?? 'Action plan dari support needed',
                        'status_action_plan' => 'Open',
                    ]);
                }
            }

            // Hapus data warroom yang supportneeded-nya sudah tidak berstatus 'Action'
            $actionIds = Supportneeded::where('status', 'Action')->pluck('id');
            Newwarroom::whereNotNull('supportneeded_id')
                ->whereNotIn('supportneeded_id', $actionIds)
                ->delete();

            DB::commit();

            return redirect()->route('newwarroom.index')->with('success', 'Data berhasil disinkronkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('newwarroom.index')->with('error', 'Terjadi kesalahan saat sinkronisasi: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form tambah data.
     */
    public function create()
    {
        return view('newwarroom.create');
    }

    /**
     * Simpan data baru dengan action plans.
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

        // Validasi action plans
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
            // Simpan data warroom
            $warroom = Newwarroom::create($validated);

            // Simpan action plans
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

            return redirect()->route('newwarroom.index')->with('success', 'Data warroom dan action plans berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan detail warroom dengan action plans.
     */
    public function show(Newwarroom $newwarroom)
    {
        $newwarroom->load('actionPlans', 'supportneeded');
        return view('newwarroom.show', compact('newwarroom'));
    }

    /**
     * Tampilkan form edit.
     */
    public function edit(Newwarroom $newwarroom)
    {
        $newwarroom->load('actionPlans');
        return view('newwarroom.edit', ['data' => $newwarroom]);
    }

    /**
     * Update data warroom dan action plans.
     */
    public function update(Request $request, Newwarroom $newwarroom)
    {
        // DEBUG: Log semua request data
        \Log::info('Update Request Data:', $request->all());

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

        // DEBUG: Log validated data
        \Log::info('Validated Data:', $validated);

        // Validasi action plans
        $jumlah = (int) $validated['jumlah_action_plan'];

        $actionPlanData = [];
        for ($i = 1; $i <= $jumlah; $i++) {
            $actionPlanValidation = $request->validate([
                "action_plan_{$i}" => 'required|string',
                "update_action_plan_{$i}" => 'nullable|string',
                "status_action_plan_{$i}" => 'required|in:Open,Progress,Need Discuss,Eskalasi,Done',
            ]);

            $actionPlanData[$i] = [
                'action_plan' => $request->input("action_plan_{$i}"),
                'update_action_plan' => $request->input("update_action_plan_{$i}"),
                'status_action_plan' => $request->input("status_action_plan_{$i}"),
            ];
        }

        // DEBUG: Log action plan data
        \Log::info('Action Plan Data:', $actionPlanData);

        DB::beginTransaction();

        try {
            $old = $newwarroom->toArray();

            // Update data warroom
            $newwarroom->update($validated);

            // DEBUG: Log sebelum hapus action plans
            \Log::info('Existing Action Plans:', $newwarroom->actionPlans->toArray());

            // Hapus action plans lama
            $newwarroom->actionPlans()->delete();

            // Buat action plans baru
            for ($i = 1; $i <= $jumlah; $i++) {
                $actionPlan = ActionPlan::create([
                    'newwarroom_id' => $newwarroom->id,
                    'plan_number' => $i,
                    'action_plan' => $actionPlanData[$i]['action_plan'],
                    'update_action_plan' => $actionPlanData[$i]['update_action_plan'],
                    'status_action_plan' => $actionPlanData[$i]['status_action_plan'],
                ]);

                // DEBUG: Log action plan yang dibuat
                \Log::info("Created Action Plan {$i}:", $actionPlan->toArray());
            }

            // Log activity (pastikan fungsi log_activity ada)
            if (function_exists('log_activity')) {
                log_activity('update', $newwarroom, 'Mengubah data Warroom dengan ' . $jumlah . ' action plans', [
                    'before' => $old,
                    'after' => $newwarroom->fresh()->toArray(),
                ]);
            }

            DB::commit();

            return redirect()->route('newwarroom.index')->with('success', 'Data warroom dan action plans berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Update Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus data warroom beserta action plans.
     */
    public function destroy(Newwarroom $newwarroom)
    {
        DB::beginTransaction();

        try {
            log_activity('delete', $newwarroom, 'Menghapus data Warroom: ' . $newwarroom->agenda . ' dengan ' . $newwarroom->actionPlans->count() . ' action plans');

            // Action plans akan terhapus otomatis karena foreign key cascade
            $newwarroom->delete();

            DB::commit();

            return redirect()->route('newwarroom.index')->with('success', 'Data warroom dan action plans berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('newwarroom.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint untuk mendapatkan data action plans berdasarkan warroom
     */
    public function getActionPlans(Newwarroom $newwarroom)
    {
        $actionPlans = $newwarroom->actionPlans()->orderBy('plan_number')->get();
        return response()->json($actionPlans);
    }

    /**
     * Update status action plan individual
     */
    public function updateActionPlanStatus(Request $request, ActionPlan $actionPlan)
    {
        $validated = $request->validate([
            'status_action_plan' => 'required|in:Open,Progress,Need Discuss,Eskalasi,Done',
            'update_action_plan' => 'nullable|string',
        ]);

        $actionPlan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status action plan berhasil diperbarui',
            'data' => $actionPlan
        ]);
    }
}
