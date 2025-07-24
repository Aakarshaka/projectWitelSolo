<?php

namespace App\Http\Controllers;

use App\Models\Supportneeded;
use App\Models\Newwarroom;
use App\Models\ActionPlan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SupportneededController extends Controller
{
    public function index(Request $request)
    {
        $query = Supportneeded::query();

        // Apply filters - sesuai dengan form di blade
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('progress')) {
            $query->where('progress', $request->progress);
        }

        if ($request->filled('unit_or_telda')) {
            $query->where('unit_or_telda', $request->unit_or_telda);
        }

        if ($request->filled('uic')) {
            $uicValue = $request->uic;
            $query->where(function ($q) use ($uicValue) {
                $q->where('uic', 'like', '%' . $uicValue . '%')
                    ->orWhere('uic', '=', $uicValue)
                    ->orWhereRaw("FIND_IN_SET(?, REPLACE(uic, ' ', ''))", [$uicValue])
                    ->orWhereRaw("FIND_IN_SET(?, uic)", [$uicValue]);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $searchTerm = '%' . $request->search . '%';
                $q->where('agenda', 'like', $searchTerm)
                    ->orWhere('unit_or_telda', 'like', $searchTerm)
                    ->orWhere('notes_to_follow_up', 'like', $searchTerm)
                    ->orWhere('uic', 'like', $searchTerm)
                    ->orWhere('progress', 'like', $searchTerm)
                    ->orWhere('status', 'like', $searchTerm)
                    ->orWhere('response_uic', 'like', $searchTerm);
            });
        }

        // Order by start_date with nulls last
        $items = $query->orderByRaw('start_date IS NULL')
            ->orderBy('start_date', 'asc')
            ->get();

        // Calculate statistics
        $allItems = Supportneeded::all();
        $total = $allItems->count();
        $close = $allItems->where('progress', 'Done')->count();
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;

        // Calculate average progress
        $progressMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'On Progress' => 75,
            'Done' => 100,
        ];

        $totalProgress = 0;
        $count = 0;

        foreach ($allItems as $item) {
            if (isset($progressMap[$item->progress])) {
                $totalProgress += $progressMap[$item->progress];
                $count++;
            }
        }

        $avgProgress = $count > 0 ? round($totalProgress / $count, 1) : 0;

        return view('supportneeded.supportneeded', compact(
            'items',
            'total',
            'close',
            'closePercentage',
            'avgProgress'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'agenda' => 'required|string|max:255',
                'unit_or_telda' => 'nullable|string|max:255',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'notes_to_follow_up' => 'nullable|string',
                'uic' => 'nullable|string',
                'progress' => 'required|string|in:Open,Need Discuss,On Progress,Done',
                'status' => 'nullable|string|in:Action,Eskalasi,Support Needed',
                'response_uic' => 'nullable|string',
            ]);

            // Initialize UIC approvals
            $validated['uic_approvals'] = $this->initializeUicApprovals($validated['uic'] ?? '');

            // Determine status based on UIC and unit_or_telda if not provided
            if (!isset($validated['status']) || empty($validated['status'])) {
                $validated['status'] = $this->determineStatusByUic(
                    $validated['uic'] ?? '',
                    $validated['unit_or_telda'] ?? ''
                );
            }

            // Calculate dates and off_day
            $this->calculateDatesAndOffDay($validated);

            // Calculate completion percentage
            $validated['complete'] = $this->getProgressPercentage($validated['progress']);

            // If progress is Done, set end_date and approve all UICs
            if ($validated['progress'] === 'Done') {
                if (!isset($validated['end_date']) || !$validated['end_date']) {
                    $validated['end_date'] = now()->format('Y-m-d');
                }

                // Auto-approve all UICs when Done
                if ($validated['uic']) {
                    $uics = explode(',', $validated['uic']);
                    $approvals = [];
                    foreach ($uics as $uic) {
                        $approvals[trim($uic)] = true;
                    }
                    $validated['uic_approvals'] = json_encode($approvals);
                }
            }

            $support = Supportneeded::create($validated);

            Log::info('Created Supportneeded record', [
                'id' => $support->id,
                'status' => $support->status,
                'uic' => $support->uic,
                'agenda' => $support->agenda
            ]);

            // Log activity if function exists
            if (function_exists('log_activity')) {
                log_activity('create', $support, 'Menambahkan data Support Needed');
            }

            // SYNC TO WARROOM: Jika status = 'Action'
            if ($support->status === 'Action') {
                $this->syncToWarroom($support);
                Log::info('Auto-synced to warroom after creation', [
                    'support_id' => $support->id,
                    'status' => $support->status,
                    'uic' => $support->uic
                ]);
            }

            DB::commit();

            $message = 'Data berhasil disimpan.';
            if ($support->status === 'Action') {
                $message .= ' Data otomatis ditambahkan ke Warroom.';
            }

            return redirect()->route('supportneeded.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating support needed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Supportneeded $supportneeded)
    {
        DB::beginTransaction();

        try {
            $oldData = $supportneeded->toArray();
            $oldStatus = $supportneeded->status;
            $oldUic = $supportneeded->uic;

            $validated = $request->validate([
                'agenda' => 'required|string|max:255',
                'unit_or_telda' => 'nullable|string|max:255',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'notes_to_follow_up' => 'nullable|string',
                'uic' => 'nullable|string',
                'progress' => 'required|string|in:Open,Need Discuss,On Progress,Done',
                'status' => 'nullable|string|in:Action,Eskalasi,Support Needed',
                'response_uic' => 'nullable|string',
            ]);

            // Handle UIC approvals dengan mempertahankan approval yang sudah ada
            $validated['uic_approvals'] = $this->handleUicApprovalsUpdate(
                $supportneeded,
                $validated['uic'] ?? ''
            );

            // Determine status based on UIC and unit_or_telda if not provided
            if (!isset($validated['status']) || empty($validated['status'])) {
                $validated['status'] = $this->determineStatusByUic(
                    $validated['uic'] ?? '',
                    $validated['unit_or_telda'] ?? ''
                );
            }

            // Handle progress change to/from Done
            $this->handleProgressChange($supportneeded, $validated);

            // Calculate dates and off_day
            $this->calculateDatesAndOffDay($validated);

            // Calculate completion percentage
            $validated['complete'] = $this->getProgressPercentage($validated['progress']);

            // If progress changes to Done, auto-approve all UICs
            if ($validated['progress'] === 'Done' && $supportneeded->progress !== 'Done') {
                if ($validated['uic']) {
                    $uics = explode(',', $validated['uic']);
                    $approvals = [];
                    foreach ($uics as $uic) {
                        $approvals[trim($uic)] = true;
                    }
                    $validated['uic_approvals'] = json_encode($approvals);
                }
            }

            // Update supportneeded
            $supportneeded->update($validated);

            Log::info('Updated Supportneeded record', [
                'id' => $supportneeded->id,
                'old_status' => $oldStatus,
                'new_status' => $supportneeded->status,
                'old_uic' => $oldUic,
                'new_uic' => $supportneeded->uic,
                'agenda' => $supportneeded->agenda
            ]);

            // Log activity if function exists
            if (function_exists('log_activity')) {
                log_activity('update', $supportneeded, 'Memperbarui data Support Needed', [
                    'before' => $oldData,
                    'after' => $supportneeded->toArray(),
                ]);
            }

            // HANDLE WARROOM SYNC - perbaikan untuk semua perubahan
            $this->handleWarroomSyncOnUpdate($supportneeded, $oldStatus);

            DB::commit();

            $message = $this->generateUpdateMessage($supportneeded, $oldStatus);

            return redirect()->route('supportneeded.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating support needed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    private function generateUpdateMessage($supportneeded, $oldStatus)
    {
        $message = 'Data berhasil diperbarui.';

        if ($supportneeded->status === 'Action' && $oldStatus !== 'Action') {
            $message .= ' Data otomatis ditambahkan ke Warroom.';
        } elseif ($supportneeded->status !== 'Action' && $oldStatus === 'Action') {
            $message .= ' Data dihapus dari Warroom karena status berubah.';
        } elseif ($supportneeded->status === 'Action') {
            $message .= ' Data Warroom ikut diperbarui.';
        }

        return $message;
    }

    /**
     * Handle warroom sync saat update berdasarkan perubahan status
     */
    private function handleWarroomSyncOnUpdate(Supportneeded $support, $oldStatus)
    {
        $newStatus = $support->status;

        Log::info('Handling warroom sync on update', [
            'support_id' => $support->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'uic' => $support->uic
        ]);

        // Selalu sync jika status adalah Action, baik baru maupun sudah Action sebelumnya
        if ($newStatus === 'Action') {
            $this->syncToWarroom($support);
            Log::info('Synced to warroom due to Action status', [
                'support_id' => $support->id,
                'was_action_before' => $oldStatus === 'Action'
            ]);
        }
        // Jika status berubah dari Action ke yang lain, hapus dari warroom
        elseif ($oldStatus === 'Action' && $newStatus !== 'Action') {
            $this->removeFromWarroom($support);
            Log::info('Removed from warroom due to status change', [
                'support_id' => $support->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
        }
    }

    /**
     * Method untuk menghapus data dari warroom
     */
    private function removeFromWarroom(Supportneeded $support)
    {
        try {
            $warroom = Newwarroom::where('supportneeded_id', $support->id)->first();

            if ($warroom) {
                Log::info('Removing warroom and related data', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id
                ]);

                // Hapus action plans terkait terlebih dahulu
                $deletedPlans = $warroom->actionPlans()->delete();

                // Hapus warroom
                $warroom->delete();

                Log::info('Successfully removed warroom and action plans', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'deleted_action_plans' => $deletedPlans
                ]);
            } else {
                Log::info('No warroom found to remove', [
                    'support_id' => $support->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error removing from warroom: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'trace' => $e->getTraceAsString()
            ]);
            // Jangan throw exception karena ini operasi background
        }
    }

    /**
     * Update approval status - sesuai dengan AJAX call di blade
     */
    public function updateApproval(Request $request, $id)
    {
        try {
            $request->validate([
                'uic' => 'required|string',
                'approved' => 'required|boolean'
            ]);

            $item = SupportNeeded::findOrFail($id);

            // Get current approvals
            $approvals = $item->uic_approvals ? json_decode($item->uic_approvals, true) : [];

            // Update specific UIC approval
            $approvals[$request->uic] = $request->approved;

            // Save updated approvals
            $item->uic_approvals = json_encode($approvals);
            $item->save();

            Log::info('Updated UIC approval', [
                'support_id' => $id,
                'uic' => $request->uic,
                'approved' => $request->approved
            ]);

            // Sync to warroom if status is Action
            if ($item->status === 'Action') {
                $this->syncToWarroom($item);
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval status updated successfully',
                'approvals' => $approvals
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating approval: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update approval status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update progress status - sesuai dengan AJAX call di blade
     */
    public function updateProgress(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'progress' => 'required|string|in:Open,Need Discuss,On Progress,Done'
            ]);

            $item = SupportNeeded::findOrFail($id);
            $oldProgress = $item->progress;

            // Update progress
            $item->progress = $request->progress;

            // If progress is Done, mark all UICs as approved and set end_date
            if ($request->progress === 'Done') {
                if ($item->uic) {
                    $uics = explode(',', $item->uic);
                    $approvals = [];

                    foreach ($uics as $uic) {
                        $approvals[trim($uic)] = true;
                    }

                    $item->uic_approvals = json_encode($approvals);
                }

                // Set end_date to today if not already set
                if (!$item->end_date) {
                    $item->end_date = now()->format('Y-m-d');
                }
            }

            // Calculate completion percentage
            $item->complete = $this->getProgressPercentage($item->progress);

            $item->save();

            Log::info('Updated progress', [
                'support_id' => $id,
                'old_progress' => $oldProgress,
                'new_progress' => $item->progress
            ]);

            // Sync to warroom if status is Action
            if ($item->status === 'Action') {
                $this->syncToWarroom($item);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Progress updated successfully',
                'progress' => $item->progress,
                'end_date' => $item->end_date
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating progress: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update progress: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Supportneeded $supportneeded)
    {
        DB::beginTransaction();

        try {
            // Delete related warroom entry if exists
            $warroom = Newwarroom::where('supportneeded_id', $supportneeded->id)->first();
            if ($warroom) {
                // Delete action plans first
                $warroom->actionPlans()->delete();
                // Delete warroom
                $warroom->delete();

                Log::info('Deleted related warroom and action plans', [
                    'support_id' => $supportneeded->id,
                    'warroom_id' => $warroom->id
                ]);
            }

            $supportneeded->delete();

            // Log activity if function exists
            if (function_exists('log_activity')) {
                log_activity('delete', $supportneeded, 'Menghapus data Support Needed');
            }

            DB::commit();

            return redirect()->route('supportneeded.index')
                ->with('success', 'Data berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting support needed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function export()
    {
        // Placeholder for Excel export functionality
        // You can implement this using packages like Laravel Excel
        return redirect()->route('supportneeded.index')
            ->with('info', 'Export feature will be implemented soon.');
    }

    // ===== PRIVATE HELPER METHODS =====

    private function determineStatusByUic($uic, $unitOrTelda = '')
    {
        $escalationUics = ['RLEGS', 'RSO REGIONAL', 'ED', 'TIF', 'TSEL', 'GSD', 'RSMES', 'BPPLP', 'SSS'];
        $supportNeededUics = ['BS', 'GS', 'RSO WITEL', 'SSGS', 'PRQ'];

        // If both unit_or_telda and uic are empty, return empty status
        if (empty($unitOrTelda) && empty($uic)) {
            return '';
        }

        // If uic is empty, return empty status
        if (empty($uic)) {
            return '';
        }

        // Split UIC into array for checking
        $uics = explode(',', $uic);
        $trimmedUics = array_map('trim', $uics);

        // Priority 1: If unit_or_telda matches any UIC in the list, return Action
        if (!empty($unitOrTelda) && in_array($unitOrTelda, $trimmedUics)) {
            return 'Action';
        }

        // Priority 2: Check for escalation UICs
        foreach ($trimmedUics as $trimmedUic) {
            if (in_array($trimmedUic, $escalationUics)) {
                return 'Eskalasi';
            }
        }

        // Priority 3: Check for support needed UICs
        foreach ($trimmedUics as $trimmedUic) {
            if (in_array($trimmedUic, $supportNeededUics)) {
                return 'Support Needed';
            }
        }

        // If no matching UIC found, return empty status
        return '';
    }

    private function initializeUicApprovals($uic)
    {
        if (empty($uic)) {
            return json_encode([]);
        }

        $uics = explode(',', $uic);
        $approvals = [];

        foreach ($uics as $singleUic) {
            $approvals[trim($singleUic)] = false;
        }

        return json_encode($approvals);
    }

    private function handleUicApprovalsUpdate($supportneeded, $uic)
    {
        $existingApprovals = $supportneeded->uic_approvals ?
            json_decode($supportneeded->uic_approvals, true) : [];

        if (empty($uic)) {
            return json_encode([]);
        }

        $uics = explode(',', $uic);
        $approvals = [];

        foreach ($uics as $singleUic) {
            $trimmedUic = trim($singleUic);
            $approvals[$trimmedUic] = $existingApprovals[$trimmedUic] ?? false;
        }

        return json_encode($approvals);
    }

    private function handleProgressChange($supportneeded, &$validated)
    {
        // If progress changes to Done, set end_date to today
        if ($validated['progress'] === 'Done' && $supportneeded->progress !== 'Done') {
            $validated['end_date'] = Carbon::now()->format('Y-m-d');
        }
        // If progress changes from Done to something else, clear end_date
        elseif ($validated['progress'] !== 'Done' && $supportneeded->progress === 'Done') {
            $validated['end_date'] = null;
        }
    }

    private function calculateDatesAndOffDay(&$validated)
    {
        $startDate = $validated['start_date'] ?? null;

        if ($startDate) {
            if ($validated['progress'] === 'Done' && isset($validated['end_date'])) {
                $diffInHours = Carbon::parse($startDate)
                    ->diffInHours(Carbon::parse($validated['end_date']));
            } else {
                $diffInHours = Carbon::parse($startDate)->diffInHours(Carbon::now());
            }
            $validated['off_day'] = ceil($diffInHours / 24);
        } else {
            $validated['off_day'] = 0;
        }
    }

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
     * MAIN SYNC FUNCTION - Sync to warroom dengan logic yang diperbaiki
     */
    private function syncToWarroom($support)
    {
        try {
            Log::info('Starting sync to warroom', [
                'support_id' => $support->id,
                'status' => $support->status,
                'agenda' => $support->agenda,
                'uic' => $support->uic
            ]);

            // Only sync if status is 'Action'
            if ($support->status !== 'Action') {
                Log::info('Status is not Action, removing from warroom if exists', [
                    'support_id' => $support->id,
                    'status' => $support->status
                ]);
                $this->removeFromWarroom($support);
                return;
            }

            // Cari warroom yang sudah ada berdasarkan supportneeded_id
            $warroom = Newwarroom::where('supportneeded_id', $support->id)->first();

            // Prepare warroom data - pastikan semua field tersinkronisasi
            $warroomData = [
                'tgl' => $support->start_date,
                'agenda' => $support->agenda,
                'unit_or_telda' => $support->unit_or_telda,
                'start_date' => $support->start_date,
                'end_date' => $support->end_date,
                'off_day' => $support->off_day ?? 0,
                'notes_to_follow_up' => $support->notes_to_follow_up,
                'uic' => $support->uic, // Pastikan UIC tersinkronisasi dengan benar
                'uic_approvals' => $support->uic_approvals,
                'progress' => $support->progress,
                'complete' => $support->complete ?? $this->getProgressPercentage($support->progress),
                'status' => $support->status,
                'response_uic' => $support->response_uic,
                'support_needed' => $support->notes_to_follow_up,
                'jumlah_action_plan' => 1,
                'supportneeded_id' => $support->id, // Pastikan foreign key ada
            ];

            if ($warroom) {
                // Update existing warroom - pastikan semua field terupdate
                $warroom->update($warroomData);
                Log::info('Updated existing warroom', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'uic_synced' => $support->uic
                ]);
            } else {
                // Create new warroom dengan semua data
                $warroom = Newwarroom::create($warroomData);
                Log::info('Created new warroom', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'uic_created' => $support->uic
                ]);
            }

            // Handle action plan - pastikan action plan tersinkronisasi
            $this->syncActionPlan($warroom, $support);

            Log::info('Successfully synced to warroom', [
                'support_id' => $support->id,
                'warroom_id' => $warroom->id,
                'final_uic' => $warroom->uic
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing to warroom: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'trace' => $e->getTraceAsString()
            ]);
            // Jangan throw exception karena ini operasi background sync
        }
    }

    private function syncActionPlan($warroom, $support)
    {
        try {
            // Cari action plan yang sudah ada atau buat baru
            $actionPlan = ActionPlan::where('newwarroom_id', $warroom->id)
                ->where('plan_number', 1)
                ->first();

            $actionPlanData = [
                'action_plan' => $support->notes_to_follow_up ?? 'Action plan dari support needed',
                'status_action_plan' => $this->mapProgressToActionPlanStatus($support->progress),
            ];

            if ($actionPlan) {
                // Update existing action plan
                $actionPlan->update($actionPlanData);
                Log::info('Updated existing action plan', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'action_plan_id' => $actionPlan->id,
                    'status' => $actionPlanData['status_action_plan']
                ]);
            } else {
                // Create new action plan
                $actionPlanData['newwarroom_id'] = $warroom->id;
                $actionPlanData['plan_number'] = 1;

                $newActionPlan = ActionPlan::create($actionPlanData);
                Log::info('Created new action plan', [
                    'support_id' => $support->id,
                    'warroom_id' => $warroom->id,
                    'action_plan_id' => $newActionPlan->id,
                    'status' => $actionPlanData['status_action_plan']
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error syncing action plan: ' . $e->getMessage(), [
                'support_id' => $support->id,
                'warroom_id' => $warroom->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }


    /**
     * Map Supportneeded progress to ActionPlan status
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
}