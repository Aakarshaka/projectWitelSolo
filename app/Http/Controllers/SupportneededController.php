<?php

namespace App\Http\Controllers;

use App\Models\Supportneeded;
use Illuminate\Http\Request;

class SupportneededController extends Controller
{
    public function index(Request $request)
    {
        $query = Supportneeded::query();

        // Optional: filtering
        if ($request->type_agenda) {
            $query->where('agenda', $request->type_agenda);
        }

        if ($request->unit_or_witel) {
            $query->where('unit_or_telda', $request->unit_or_witel);
        }

        if ($request->uic) {
            $query->where('uic', $request->uic);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('agenda', 'like', '%' . $request->search . '%')
                    ->orWhere('unit_or_telda', 'like', '%' . $request->search . '%')
                    ->orWhere('notes_to_follow_up', 'like', '%' . $request->search . '%')
                    ->orWhere('uic', 'like', '%' . $request->search . '%')
                    ->orWhere('progress', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%')
                    ->orWhere('response_uic', 'like', '%' . $request->search . '%');
            });
        }


        // Data untuk table
        $items = $query->paginate(10);

        // Summary Data
        $allItems = Supportneeded::all();
        $total = $allItems->count();
        $close = $allItems->where('progress', 'Done')->count();
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;

        // Hitung rata-rata progress
        $progressMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'Progress' => 75,
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
        $validated = $request->validate([
            'agenda' => 'required|string',
            'unit_or_telda' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'off_day' => 'nullable|integer',
            'notes_to_follow_up' => 'nullable|string',
            'uic' => 'nullable|string',
            'progress' => 'nullable|string',
            'complete' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|string',
            'response_uic' => 'nullable|string',
        ]);

        Supportneeded::create($validated);
        return back()->with('success', 'Agenda created');
    }

    public function update(Request $request, Supportneeded $supportneeded)
    {
        $validated = $request->validate([
            'agenda' => 'required|string',
            'unit_or_telda' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'off_day' => 'nullable|integer',
            'notes_to_follow_up' => 'nullable|string',
            'uic' => 'nullable|string',
            'progress' => 'nullable|string',
            'complete' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|string',
            'response_uic' => 'nullable|string',
        ]);

        $supportneeded->update($validated);
        return back()->with('success', 'Agenda updated');
    }

    public function destroy(Supportneeded $supportneeded)
    {
        $supportneeded->delete();
        return back()->with('success', 'Agenda deleted');
    }
}
