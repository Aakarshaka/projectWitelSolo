<?php

namespace App\Http\Controllers;

use App\Models\supportneeded;
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

        $items = $query->paginate(10);

        // Optional: Summary data for top-right widgets
        $total = Supportneeded::count();
        $close = Supportneeded::where('status', 'Done')->count();
        $avgProgress = round(Supportneeded::avg('complete'));

        return view('supportneeded.supportneeded', compact('items', 'total', 'close', 'avgProgress'));
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
