<?php

namespace App\Http\Controllers;

use App\Models\Newwarroom;
use App\Models\ActionPlan;
use App\Models\Supportneeded;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * CATATAN: Ini hanya untuk sinkronisasi manual, karena auto-sync sudah ada di model events
     */
    public function syncFromSupportneeded(): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Get all data with status 'Action' from supportneeded
            $supportData = Supportneeded::where('status', 'Action')
                ->orderByRaw('start_date IS NULL')
                ->orderBy('start_date', 'asc')
                ->get();

            Log::info('Starting manual sync from Supportneeded', ['count' => $supportData->count()]);

            $syncedCount = 0;
            foreach ($supportData as $item) {
                // Gunakan method dari model Supportneeded untuk konsistensi
                $this->ensureWarroomSync($item);
                $syncedCount++;
            }

            // Hapus data warroom yang supportneeded-nya sudah tidak berstatus 'Action'
            $cleanedCount = $this->cleanupNonActionWarrooms();

            DB::commit();

            Log::info('Manual sync completed successfully', [
                'synced' => $syncedCount,
                'cleaned' => $cleanedCount
            ]);

            $message = "Data berhasil disinkronkan. {$syncedCount} item disinkronkan, {$cleanedCount} item dibersihkan.";
            return redirect()->route('newwarroom.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error during manual sync: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('newwarroom.index')->with('error', 'Terjadi kesalahan saat sinkronisasi: ' . $e->getMessage());
        }
    }

    /**
     * Pastikan warroom tersinkronisasi dengan supportneeded
     * Menggunakan logika yang sama dengan model events
     */
    private function ensureWarroomSync(Supportneeded $support)
    {
        try {
            // Hanya sync jika status adalah 'Action'
            if ($support->status !== 'Action') {
                $this->removeWarroomForSupport($support);
                return;
            }

            // Cari warroom yang sudah ada atau buat baru
            $warroom = Newwarroom::where('supportneeded_id', $support->id)->first();

            $warroomData = [
                'tgl' => $support->start_date,
                'agenda' => $support->agenda,
                'unit_or_telda' => $support->unit_or_telda,
                'start_date' => $support->start_date,
                'end_date' => $support->end_date,
                'off_day' => $support->off_day,
                'notes_to_follow_up' => $support->notes_to_follow_up,
                'uic' => $support->uic,
                'uic_approvals' => $support->uic_approvals,
                'progress' => $support->progress,
                'complete' => $support->complete,
                'status' => $support->status,
                'response_uic' => $support->response_uic,
                'support_needed' => $support->notes_to_follow_up,
                'jumlah_action_plan' => 1,
            ];

            if ($warroom) {
                // Update existing warroom
                $warroom->update($warroomData);
                Log::info('Updated existing warroom in manual sync', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id
                ]);
            } else {
                // Create new warroom
                $warroomData['supportneeded_id'] = $support->id;
                $warroom = Newwarroom::create($warroomData);
                
                Log::info('Created new warroom in manual sync', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id
                ]);
            }

            // Handle action plan
            $existingActionPlan = $warroom->actionPlans()->first();
            if (!$existingActionPlan) {
                // Create default action plan
                ActionPlan::create([
                    'newwarroom_id' => $warroom->id,
                    'plan_number' => 1,
                    'action_plan' => $support->notes_to_follow_up ?? 'Action plan dari support needed',
                    'status_action_plan' => $this->mapProgressToActionPlanStatus($support->progress),
                ]);
                
                Log::info('Created default action plan in manual sync', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id
                ]);
            } else {
                // Update existing action plan
                $existingActionPlan->update([
                    'action_plan' => $support->notes_to_follow_up ?? $existingActionPlan->action_plan,
                    'status_action_plan' => $this->mapProgressToActionPlanStatus($support->progress)
                ]);
                
                Log::info('Updated existing action plan in manual sync', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'action_plan_id' => $existingActionPlan->id
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in ensureWarroomSync: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Hapus warroom untuk supportneeded tertentu
     */
    private function removeWarroomForSupport(Supportneeded $support)
    {
        try {
            $warroom = Newwarroom::where('supportneeded_id', $support->id)->first();
            
            if ($warroom) {
                // Hapus action plans terkait
                $warroom->actionPlans()->delete();
                
                // Hapus warroom
                $warroom->delete();
                
                Log::info('Successfully removed warroom and action plans in manual sync', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error removing warroom for support: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Clean up warroom entries that no longer have 'Action' status in supportneeded
     */
    private function cleanupNonActionWarrooms()
    {
        try {
            // Get IDs of supportneeded items with 'Action' status
            $actionIds = Supportneeded::where('status', 'Action')->pluck('id');

            // Find warroom entries that should be deleted
            $warroomsToDelete = Newwarroom::whereNotNull('supportneeded_id')
                ->whereNotIn('supportneeded_id', $actionIds)
                ->get();

            Log::info('Cleaning up non-action warrooms', [
                'count' => $warroomsToDelete->count()
            ]);

            $deletedCount = 0;
            // Delete action plans first, then warrooms
            foreach ($warroomsToDelete as $warroom) {
                $warroom->actionPlans()->delete();
                $warroom->delete();
                $deletedCount++;

                Log::info('Deleted warroom and its action plans', [
                    'warroom_id' => $warroom->id,
                    'supportneeded_id' => $warroom->supportneeded_id
                ]);
            }

            return $deletedCount;

        } catch (\Exception $e) {
            Log::error('Error during cleanup: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Map Supportneeded progress to ActionPlan status
     * Harus sama dengan mapping di model Supportneeded
     */
    private function mapProgressToActionPlanStatus($progress)
    {
        switch ($progress) {
            case 'Open':
                return 'Open';
            case 'Need Discuss':
                return 'Need Discuss';
            case 'On Progress':
                return 'Progress';
            case 'Done':
                return 'Done';
            default:
                return 'Open';
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
            'supportneeded_id' => 'nullable|integer|exists:supportneededs,id',
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
            // Jika ada supportneeded_id, pastikan data konsisten
            if ($validated['supportneeded_id']) {
                $support = Supportneeded::find($validated['supportneeded_id']);
                if ($support) {
                    // Sync some data from supportneeded untuk konsistensi
                    $validated['agenda'] = $support->agenda;
                    $validated['uic'] = $support->uic;
                    $validated['tgl'] = $support->start_date;
                    
                    // Copy additional fields from supportneeded
                    $validated['unit_or_telda'] = $support->unit_or_telda;
                    $validated['start_date'] = $support->start_date;
                    $validated['end_date'] = $support->end_date;
                    $validated['off_day'] = $support->off_day;
                    $validated['notes_to_follow_up'] = $support->notes_to_follow_up;
                    $validated['uic_approvals'] = $support->uic_approvals;
                    $validated['progress'] = $support->progress;
                    $validated['complete'] = $support->complete;
                    $validated['status'] = $support->status;
                    $validated['response_uic'] = $support->response_uic;
                    
                    // Ensure support_needed is filled
                    if (empty($validated['support_needed'])) {
                        $validated['support_needed'] = $support->notes_to_follow_up;
                    }
                }
            }

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

            if (function_exists('log_activity')) {
                log_activity('create', $warroom, 'Menambahkan data Warroom dengan ' . $jumlah . ' action plans');
            }

            DB::commit();

            return redirect()->route('newwarroom.index')->with('success', 'Data warroom dan action plans berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating warroom: ' . $e->getMessage());
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
        Log::info('Update Request Data:', $request->all());

        $validated = $request->validate([
            'tgl' => 'nullable|date',
            'agenda' => 'required|string',
            'uic' => 'nullable|string',
            'peserta' => 'nullable|string',
            'pembahasan' => 'nullable|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'required|integer|min:1|max:10',
            'supportneeded_id' => 'nullable|integer|exists:supportneededs,id',
        ]);

        // DEBUG: Log validated data
        Log::info('Validated Data:', $validated);

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

        DB::beginTransaction();

        try {
            $oldData = $newwarroom->toArray();

            // Update warroom data
            $newwarroom->update($validated);

            // Delete existing action plans
            $newwarroom->actionPlans()->delete();

            // Create new action plans
            for ($i = 1; $i <= $jumlah; $i++) {
                ActionPlan::create([
                    'newwarroom_id' => $newwarroom->id,
                    'plan_number' => $i,
                    'action_plan' => $actionPlanData[$i]['action_plan'],
                    'update_action_plan' => $actionPlanData[$i]['update_action_plan'],
                    'status_action_plan' => $actionPlanData[$i]['status_action_plan'],
                ]);
            }

            // If this warroom is linked to supportneeded, sync back the changes
            // PENTING: Hanya sync jika warroom diubah secara manual, bukan dari auto-sync
            if ($newwarroom->supportneeded_id && !$request->has('_from_auto_sync')) {
                $this->syncWarroomBackToSupport($newwarroom, $actionPlanData);
            }

            if (function_exists('log_activity')) {
                log_activity('update', $newwarroom, 'Memperbarui data Warroom dengan ' . $jumlah . ' action plans', [
                    'before' => $oldData,
                    'after' => $newwarroom->toArray(),
                ]);
            }

            DB::commit();

            return redirect()->route('newwarroom.index')->with('success', 'Data warroom dan action plans berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating warroom: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Sync warroom changes back to supportneeded if linked
     * HANYA untuk warroom yang berasal dari supportneeded
     */
    private function syncWarroomBackToSupport(Newwarroom $warroom, $actionPlanData = null)
    {
        try {
            if (!$warroom->supportneeded) {
                return;
            }

            $support = $warroom->supportneeded;
            
            // Hitung progress berdasarkan action plans jika ada data action plan
            $newProgress = $support->progress; // default ke progress saat ini
            
            if ($actionPlanData && count($actionPlanData) > 0) {
                // Ambil status dari action plan pertama (plan_number = 1)
                $firstActionPlanStatus = $actionPlanData[1]['status_action_plan'];
                $newProgress = $this->mapActionPlanStatusToProgress($firstActionPlanStatus);
            }
            
            // Update relevant fields - HATI-HATI jangan overwrite semua field
            $updateData = [
                'progress' => $newProgress,
                'complete' => $this->getProgressPercentage($newProgress),
            ];

            // Hanya update notes_to_follow_up jika support_needed tidak kosong
            if (!empty($warroom->support_needed)) {
                $updateData['notes_to_follow_up'] = $warroom->support_needed;
            }

            // Update end_date jika progress menjadi Done
            if ($newProgress === 'Done' && !$support->end_date) {
                $updateData['end_date'] = now()->format('Y-m-d');
                
                // Mark all UICs as approved jika progress Done
                if ($support->uic) {
                    $uics = explode(',', $support->uic);
                    $approvals = $support->uic_approvals ? 
                        json_decode($support->uic_approvals, true) : [];
                    
                    foreach ($uics as $uic) {
                        $approvals[trim($uic)] = true;
                    }
                    $updateData['uic_approvals'] = json_encode($approvals);
                }
            }

            // Recalculate off_day
            $updateData['off_day'] = $this->calculateOffDayForSupport($support, $updateData);

            $support->update($updateData);

            Log::info('Synced warroom changes back to supportneeded', [
                'warroom_id' => $warroom->id,
                'support_id' => $support->id,
                'old_progress' => $support->progress,
                'new_progress' => $newProgress
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing warroom back to support: ' . $e->getMessage(), [
                'warroom_id' => $warroom->id,
                'trace' => $e->getTraceAsString()
            ]);
            // Don't throw exception as this is optional sync
        }
    }

    /**
     * Map ActionPlan status to Supportneeded progress
     */
    private function mapActionPlanStatusToProgress($actionPlanStatus)
    {
        switch ($actionPlanStatus) {
            case 'Open':
                return 'Open';
            case 'Need Discuss':
                return 'Need Discuss';
            case 'Progress':
                return 'On Progress';
            case 'Done':
                return 'Done';
            case 'Eskalasi':
                return 'Need Discuss'; // atau bisa disesuaikan
            default:
                return 'Open';
        }
    }

    /**
     * Calculate off_day for supportneeded (sama dengan di SupportneededController)
     */
    private function calculateOffDayForSupport($support, $updateData = [])
    {
        $startDate = $support->start_date;
        $progress = $updateData['progress'] ?? $support->progress;
        $endDate = $updateData['end_date'] ?? $support->end_date;

        if (!$startDate) {
            return 0;
        }
        
        if ($progress === 'Done' && $endDate) {
            return \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;
        } else {
            return ceil(\Carbon\Carbon::parse($startDate)->diffInHours(\Carbon\Carbon::now()) / 24);
        }
    }

    /**
     * Get progress percentage (sama dengan di SupportneededController dan Model)
     */
    private function getProgressPercentage($progress)
    {
        switch ($progress) {
            case 'Open':
                return 0;
            case 'Need Discuss':
                return 25;
            case 'On Progress':
                return 75;
            case 'Done':
                return 100;
            default:
                return 0;
        }
    }

    /**
     * Hapus data warroom dan action plans terkait.
     */
    public function destroy(Newwarroom $newwarroom)
    {
        DB::beginTransaction();

        try {
            $supportneedewdId = $newwarroom->supportneeded_id;

            // Delete action plans first
            $newwarroom->actionPlans()->delete();

            // Delete warroom
            $newwarroom->delete();

            // PENTING: Jika warroom ini terhubung ke supportneeded,
            // jangan ubah status supportneeded karena bisa jadi user memang ingin hapus warroom saja
            // Model events di Supportneeded akan handle cleanup otomatis

            if (function_exists('log_activity')) {
                log_activity('delete', $newwarroom, 'Menghapus data Warroom dan action plans terkait');
            }

            DB::commit();

            $message = 'Data warroom dan action plans berhasil dihapus.';
            if ($supportneedewdId) {
                $message .= ' Data supportneeded terkait tetap ada dan tidak terpengaruh.';
            }

            return redirect()->route('newwarroom.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting warroom: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export data (placeholder)
     */
    public function export()
    {
        // Placeholder for Excel export functionality
        return redirect()->route('newwarroom.index')
            ->with('info', 'Export feature will be implemented soon.');
    }

    /**
     * API endpoint untuk update action plan status via AJAX
     */
    public function updateActionPlanStatus(Request $request, $warroomId, $actionPlanId)
    {
        DB::beginTransaction();
        
        try {
            $request->validate([
                'status_action_plan' => 'required|string|in:Open,Progress,Need Discuss,Eskalasi,Done'
            ]);

            $warroom = Newwarroom::findOrFail($warroomId);
            $actionPlan = ActionPlan::where('id', $actionPlanId)
                ->where('newwarroom_id', $warroomId)
                ->firstOrFail();

            $oldStatus = $actionPlan->status_action_plan;
            $actionPlan->update([
                'status_action_plan' => $request->status_action_plan
            ]);

            // Jika warroom terhubung ke supportneeded, sync perubahan
            if ($warroom->supportneeded_id) {
                // Ambil semua action plans untuk menentukan overall progress
                $allActionPlans = $warroom->actionPlans;
                $statusCounts = $allActionPlans->groupBy('status_action_plan')->map->count();

                // Logic untuk menentukan progress berdasarkan action plans
                $newProgress = $this->determineProgressFromActionPlans($statusCounts);
                
                if ($newProgress !== $warroom->supportneeded->progress) {
                    // Update supportneeded progress
                    $this->updateSupportneededProgress($warroom->supportneeded, $newProgress);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status action plan berhasil diperbarui',
                'old_status' => $oldStatus,
                'new_status' => $request->status_action_plan
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating action plan status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tentukan progress supportneeded berdasarkan status action plans
     */
    private function determineProgressFromActionPlans($statusCounts)
    {
        $total = $statusCounts->sum();
        
        if ($total === 0) {
            return 'Open';
        }

        // Jika semua Done
        if ($statusCounts->get('Done', 0) === $total) {
            return 'Done';
        }

        // Jika ada yang Progress
        if ($statusCounts->get('Progress', 0) > 0) {
            return 'On Progress';
        }

        // Jika ada yang Need Discuss atau Eskalasi
        if ($statusCounts->get('Need Discuss', 0) > 0 || $statusCounts->get('Eskalasi', 0) > 0) {
            return 'Need Discuss';
        }

        // Default ke Open
        return 'Open';
    }

    /**
     * Update supportneeded progress
     */
    private function updateSupportneededProgress(Supportneeded $support, $newProgress)
    {
        $updateData = [
            'progress' => $newProgress,
            'complete' => $this->getProgressPercentage($newProgress)
        ];

        // Jika progress menjadi Done
        if ($newProgress === 'Done' && !$support->end_date) {
            $updateData['end_date'] = now()->format('Y-m-d');
            
            // Mark all UICs as approved
            if ($support->uic) {
                $uics = explode(',', $support->uic);
                $approvals = $support->uic_approvals ? 
                    json_decode($support->uic_approvals, true) : [];
                
                foreach ($uics as $uic) {
                    $approvals[trim($uic)] = true;
                }
                $updateData['uic_approvals'] = json_encode($approvals);
            }
        }

        // Recalculate off_day
        $updateData['off_day'] = $this->calculateOffDayForSupport($support, $updateData);

        $support->update($updateData);

        Log::info('Updated supportneeded progress from action plan status change', [
            'support_id' => $support->id,
            'new_progress' => $newProgress,
            'complete' => $updateData['complete']
        ]);
    }

    /**
     * API endpoint untuk bulk update action plan status
     */
    public function bulkUpdateActionPlanStatus(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $request->validate([
                'updates' => 'required|array',
                'updates.*.action_plan_id' => 'required|integer|exists:action_plans,id',
                'updates.*.status_action_plan' => 'required|string|in:Open,Progress,Need Discuss,Eskalasi,Done'
            ]);

            $updatedCount = 0;
            $warroomIds = [];

            foreach ($request->updates as $update) {
                $actionPlan = ActionPlan::findOrFail($update['action_plan_id']);
                $actionPlan->update([
                    'status_action_plan' => $update['status_action_plan']
                ]);
                
                $warroomIds[] = $actionPlan->newwarroom_id;
                $updatedCount++;
            }

            // Sync perubahan ke supportneeded untuk warroom yang terkait
            $uniqueWarroomIds = array_unique($warroomIds);
            foreach ($uniqueWarroomIds as $warroomId) {
                $warroom = Newwarroom::find($warroomId);
                if ($warroom && $warroom->supportneeded_id) {
                    $allActionPlans = $warroom->actionPlans;
                    $statusCounts = $allActionPlans->groupBy('status_action_plan')->map->count();
                    $newProgress = $this->determineProgressFromActionPlans($statusCounts);
                    
                    if ($newProgress !== $warroom->supportneeded->progress) {
                        $this->updateSupportneededProgress($warroom->supportneeded, $newProgress);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil memperbarui {$updatedCount} action plan",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error bulk updating action plan status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui action plans: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk mendapatkan statistik warroom
     */
    public function getStats(Request $request)
    {
        try {
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');

            $query = Newwarroom::with(['actionPlans', 'supportneeded']);

            if (!empty($bulan)) {
                $query->byMonth($bulan);
            }

            if (!empty($tahun)) {
                $query->byYear($tahun);
            }

            $warroomData = $query->get();

            // Statistik dasar
            $stats = [
                'total_warroom' => $warroomData->count(),
                'total_agenda' => $warroomData->pluck('agenda')->unique()->count(),
                'total_action_plans' => ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))->count(),
            ];

            // Statistik action plans berdasarkan status
            $actionPlanStats = ActionPlan::whereIn('newwarroom_id', $warroomData->pluck('id'))
                ->selectRaw('status_action_plan, COUNT(*) as count')
                ->groupBy('status_action_plan')
                ->pluck('count', 'status_action_plan')
                ->toArray();

            $stats['action_plan_by_status'] = [
                'Open' => $actionPlanStats['Open'] ?? 0,
                'Progress' => $actionPlanStats['Progress'] ?? 0,
                'Need Discuss' => $actionPlanStats['Need Discuss'] ?? 0,
                'Eskalasi' => $actionPlanStats['Eskalasi'] ?? 0,
                'Done' => $actionPlanStats['Done'] ?? 0,
            ];

            // Statistik per bulan (untuk chart)
            $monthlyStats = Newwarroom::selectRaw('MONTH(tgl) as month, YEAR(tgl) as year, COUNT(*) as count')
                ->whereNotNull('tgl')
                ->when($tahun, function($query, $tahun) {
                    return $query->where('tgl', 'like', $tahun . '%');
                })
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get()
                ->map(function($item) {
                    return [
                        'period' => sprintf('%04d-%02d', $item->year, $item->month),
                        'count' => $item->count
                    ];
                });

            $stats['monthly_data'] = $monthlyStats;

            // Top UIC berdasarkan jumlah warroom
            $topUIC = $warroomData->where('uic', '!=', null)
                ->groupBy('uic')
                ->map->count()
                ->sortDesc()
                ->take(10)
                ->map(function($count, $uic) {
                    return [
                        'uic' => $uic,
                        'count' => $count
                    ];
                })
                ->values();

            $stats['top_uic'] = $topUIC;

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting warroom stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk mendapatkan data warroom untuk DataTables
     */
    public function getDataTable(Request $request)
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $search = $request->get('search')['value'] ?? '';
            $orderColumnIndex = $request->get('order')[0]['column'] ?? 0;
            $orderDirection = $request->get('order')[0]['dir'] ?? 'asc';

            // Mapping kolom untuk order
            $columns = ['id', 'tgl', 'agenda', 'uic', 'jumlah_action_plan', 'created_at'];
            $orderColumn = $columns[$orderColumnIndex] ?? 'id';

            $query = Newwarroom::with(['actionPlans', 'supportneeded']);

            // Filter berdasarkan pencarian
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('agenda', 'like', "%{$search}%")
                      ->orWhere('uic', 'like', "%{$search}%")
                      ->orWhere('peserta', 'like', "%{$search}%")
                      ->orWhere('pembahasan', 'like', "%{$search}%");
                });
            }

            // Filter tambahan dari request
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');

            if (!empty($bulan)) {
                $query->byMonth($bulan);
            }

            if (!empty($tahun)) {
                $query->byYear($tahun);
            }

            // Total records sebelum filter
            $totalRecords = Newwarroom::count();

            // Total records setelah filter
            $filteredRecords = $query->count();

            // Ambil data dengan pagination dan sorting
            $data = $query->orderBy($orderColumn, $orderDirection)
                         ->skip($start)
                         ->take($length)
                         ->get();

            // Format data untuk DataTables
            $formattedData = $data->map(function($warroom) {
                return [
                    'id' => $warroom->id,
                    'tgl' => $warroom->tgl ? $warroom->tgl->format('d/m/Y') : '-',
                    'agenda' => $warroom->agenda,
                    'uic' => $warroom->uic ?? '-',
                    'peserta' => $warroom->peserta ?? '-',
                    'jumlah_action_plan' => $warroom->jumlah_action_plan,
                    'action_plans_summary' => $this->getActionPlansSummary($warroom->actionPlans),
                    'supportneeded_link' => $warroom->supportneeded_id ? 'Ya' : 'Tidak',
                    'created_at' => $warroom->created_at->format('d/m/Y H:i'),
                    'actions' => [
                        'show_url' => route('newwarroom.show', $warroom->id),
                        'edit_url' => route('newwarroom.edit', $warroom->id),
                        'delete_url' => route('newwarroom.destroy', $warroom->id),
                    ]
                ];
            });

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $formattedData
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting DataTable data: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Gagal mengambil data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Helper function untuk membuat ringkasan action plans
     */
    private function getActionPlansSummary($actionPlans)
    {
        if ($actionPlans->isEmpty()) {
            return 'Tidak ada action plan';
        }

        $summary = $actionPlans->groupBy('status_action_plan')
                              ->map->count()
                              ->map(function($count, $status) {
                                  return "{$status}: {$count}";
                              })
                              ->values()
                              ->implode(', ');

        return $summary;
    }

    /**
     * Clone warroom dengan action plans
     */
    public function clone(Newwarroom $newwarroom)
    {
        DB::beginTransaction();

        try {
            // Clone warroom data
            $clonedData = $newwarroom->toArray();
            unset($clonedData['id'], $clonedData['created_at'], $clonedData['updated_at']);
            
            // Reset tanggal dan tambah prefix pada agenda
            $clonedData['tgl'] = null;
            $clonedData['agenda'] = 'Copy of ' . $clonedData['agenda'];
            $clonedData['supportneeded_id'] = null; // Jangan clone koneksi ke supportneeded

            $clonedWarroom = Newwarroom::create($clonedData);

            // Clone action plans
            foreach ($newwarroom->actionPlans as $actionPlan) {
                $clonedActionPlanData = $actionPlan->toArray();
                unset($clonedActionPlanData['id'], $clonedActionPlanData['created_at'], $clonedActionPlanData['updated_at']);
                
                $clonedActionPlanData['newwarroom_id'] = $clonedWarroom->id;
                $clonedActionPlanData['status_action_plan'] = 'Open'; // Reset status

                ActionPlan::create($clonedActionPlanData);
            }

            if (function_exists('log_activity')) {
                log_activity('clone', $clonedWarroom, 'Menggandakan data Warroom dari ID: ' . $newwarroom->id);
            }

            DB::commit();

            return redirect()->route('newwarroom.edit', $clonedWarroom->id)
                           ->with('success', 'Data warroom berhasil digandakan. Silakan edit sesuai kebutuhan.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error cloning warroom: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menggandakan data: ' . $e->getMessage());
        }
    }

    /**
     * Archive warroom (soft delete alternatif)
     */
    public function archive(Newwarroom $newwarroom)
    {
        try {
            $newwarroom->update(['archived_at' => now()]);

            if (function_exists('log_activity')) {
                log_activity('archive', $newwarroom, 'Mengarsipkan data Warroom');
            }

            return redirect()->route('newwarroom.index')
                           ->with('success', 'Data warroom berhasil diarsipkan.');

        } catch (\Exception $e) {
            Log::error('Error archiving warroom: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengarsipkan data: ' . $e->getMessage());
        }
    }

    /**
     * Restore archived warroom
     */
    public function restore($id)
    {
        try {
            $warroom = Newwarroom::where('id', $id)->first();
            
            if (!$warroom) {
                return redirect()->back()->with('error', 'Data warroom tidak ditemukan.');
            }

            $warroom->update(['archived_at' => null]);

            if (function_exists('log_activity')) {
                log_activity('restore', $warroom, 'Memulihkan data Warroom dari arsip');
            }

            return redirect()->route('newwarroom.index')
                           ->with('success', 'Data warroom berhasil dipulihkan dari arsip.');

        } catch (\Exception $e) {
            Log::error('Error restoring warroom: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    /**
     * Mendapatkan data untuk dashboard summary
     */
    public function getDashboardSummary()
    {
        try {
            $today = now()->format('Y-m-d');
            $thisMonth = now()->format('Y-m');
            $thisYear = now()->format('Y');

            $summary = [
                'total_warroom' => Newwarroom::count(),
                'today_warroom' => Newwarroom::whereDate('tgl', $today)->count(),
                'this_month_warroom' => Newwarroom::where('tgl', 'like', $thisMonth . '%')->count(),
                'this_year_warroom' => Newwarroom::where('tgl', 'like', $thisYear . '%')->count(),
                
                'total_action_plans' => ActionPlan::count(),
                'open_action_plans' => ActionPlan::where('status_action_plan', 'Open')->count(),
                'progress_action_plans' => ActionPlan::where('status_action_plan', 'Progress')->count(),
                'done_action_plans' => ActionPlan::where('status_action_plan', 'Done')->count(),
                'escalated_action_plans' => ActionPlan::where('status_action_plan', 'Eskalasi')->count(),

                'linked_to_support' => Newwarroom::whereNotNull('supportneeded_id')->count(),
                'manual_warroom' => Newwarroom::whereNull('supportneeded_id')->count(),
            ];

            // Recent activities (5 terbaru)
            $recentActivities = Newwarroom::with('actionPlans')
                                        ->orderBy('updated_at', 'desc')
                                        ->take(5)
                                        ->get()
                                        ->map(function($warroom) {
                                            return [
                                                'id' => $warroom->id,
                                                'agenda' => $warroom->agenda,
                                                'tgl' => $warroom->tgl ? $warroom->tgl->format('d/m/Y') : '-',
                                                'updated_at' => $warroom->updated_at->diffForHumans(),
                                                'action_plans_count' => $warroom->actionPlans->count(),
                                                'url' => route('newwarroom.show', $warroom->id)
                                            ];
                                        });

            $summary['recent_activities'] = $recentActivities;

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting dashboard summary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil ringkasan dashboard: ' . $e->getMessage()
            ], 500);
        }
    }
}