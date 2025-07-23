<?php

namespace App\Http\Controllers;

use App\Models\Supportneeded;
use App\Models\Newwarroom;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SupportneededController extends Controller
{
    public function index(Request $request)
    {
        $query = Supportneeded::query();

        // Apply filters
        if ($request->filled('type_agenda')) {
            $query->where('agenda', $request->type_agenda);
        }

        if ($request->filled('progress')) {
            $query->where('progress', $request->progress);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

            // Determine status based on UIC if not provided
            if (!isset($validated['status'])) {
                $validated['status'] = $this->determineStatusByUic($validated['uic'] ?? '');
            }

            // Initialize UIC approvals
            $validated['uic_approvals'] = $this->initializeUicApprovals($validated['uic'] ?? '');

            // Calculate dates and off_day
            $this->calculateDatesAndOffDay($validated);

            // Calculate completion percentage
            $validated['complete'] = $this->getProgressPercentage($validated['progress']);

            $support = Supportneeded::create($validated);

            // Log activity if function exists
            if (function_exists('log_activity')) {
                log_activity('create', $support, 'Menambahkan data Support Needed');
            }

            // Sync to warroom
            $this->syncToWarroom($support);

            return redirect()->route('supportneeded.index')
                ->with('success', 'Data berhasil disimpan.');

        } catch (\Exception $e) {
            Log::error('Error creating support needed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Supportneeded $supportneeded)
    {
        try {
            $oldData = $supportneeded->toArray();

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

            // Handle UIC approvals
            $validated['uic_approvals'] = $this->handleUicApprovalsUpdate(
                $supportneeded,
                $validated['uic'] ?? ''
            );

            // Determine status based on UIC if not provided
            if (!isset($validated['status'])) {
                $validated['status'] = $this->determineStatusByUic($validated['uic'] ?? '');
            }

            // Handle progress change to/from Done
            $this->handleProgressChange($supportneeded, $validated);

            // Calculate dates and off_day
            $this->calculateDatesAndOffDay($validated);

            // Calculate completion percentage
            $validated['complete'] = $this->getProgressPercentage($validated['progress']);

            $supportneeded->update($validated);

            // Log activity if function exists
            if (function_exists('log_activity')) {
                log_activity('update', $supportneeded, 'Memperbarui data Support Needed', [
                    'before' => $oldData,
                    'after' => $supportneeded->toArray(),
                ]);
            }

            // Sync to warroom
            $this->syncToWarroom($supportneeded);

            return redirect()->route('supportneeded.index')
                ->with('success', 'Data berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating support needed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

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

            return response()->json([
                'success' => true,
                'message' => 'Approval status updated successfully',
                'approvals' => $approvals
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update approval status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update progress status
     */
    public function updateProgress(Request $request, $id)
    {
        try {
            $request->validate([
                'progress' => 'required|string|in:Open,Need Discuss,On Progress,Done'
            ]);

            $item = SupportNeeded::findOrFail($id);

            // Update progress
            $item->progress = $request->progress;

            // If progress is Done, mark all UICs as approved and set end_date
            if ($request->progress === 'Done') {
                if ($item->uic) {
                    $uics = is_array($item->uic) ? $item->uic : explode(',', $item->uic);
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

            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Progress updated successfully',
                'progress' => $item->progress,
                'end_date' => $item->end_date
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update progress: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Supportneeded $supportneeded)
    {
        try {
            // Delete related warroom entry if exists
            if ($supportneeded->warroom) {
                $supportneeded->warroom->delete();
            }

            $supportneeded->delete();

            // Log activity if function exists
            if (function_exists('log_activity')) {
                log_activity('delete', $supportneeded, 'Menghapus data Support Needed');
            }

            return redirect()->route('supportneeded.index')
                ->with('success', 'Data berhasil dihapus.');

        } catch (\Exception $e) {
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

    // Private helper methods

    private function determineStatusByUic($uic)
    {
        if (empty($uic)) {
            return 'Action';
        }

        $escalationUics = ['RLEGS', 'RSO REGIONAL', 'ED', 'TIF', 'TSEL', 'GSD', 'RSMES', 'BPPLP', 'SSS'];
        $supportNeededUics = ['BS', 'GS', 'RSO WITEL', 'SSGS', 'PRQ'];

        $uics = explode(',', $uic);

        foreach ($uics as $singleUic) {
            $trimmedUic = trim($singleUic);
            if (in_array($trimmedUic, $escalationUics)) {
                return 'Eskalasi';
            }
            if (in_array($trimmedUic, $supportNeededUics)) {
                return 'Support Needed';
            }
        }

        return 'Action';
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

    private function syncToWarroom($support)
    {
        try {
            $warroom = Newwarroom::where('supportneeded_id', $support->id)->first();

            $warroomData = [
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
            ];

            if ($warroom) {
                $warroom->update($warroomData);
            } else {
                $warroomData['supportneeded_id'] = $support->id;
                Newwarroom::create($warroomData);
            }

        } catch (\Exception $e) {
            Log::error('Error syncing to warroom: ' . $e->getMessage());
            // Don't throw exception as this is a background sync operation
        }
    }
}