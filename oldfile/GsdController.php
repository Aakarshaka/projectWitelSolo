<?php

namespace App\Http\Controllers;

use App\Models\Gsd;
use Illuminate\Http\Request;

class GsdController extends Controller
{
    public function index()
    {
        $allgsd = Gsd::all();
        $total = $allgsd->count();
        $close = $allgsd->where('status', 'Done')->count();
        $open = $total - $close;
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;
        $actualProgress = $allgsd->avg('complete');

        return view('eskalasi.gsd', compact('allgsd', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    public function create()
    {
        return view('gsd.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        $validated['complete'] = $this->mapStatusToComplete($validated['status']);
        Gsd::create($validated);
        return redirect()->route('gsd.index');
    }

    public function update(Request $request, Gsd $gsd)
    {
        $validated = $this->validateInput($request);
        $validated['complete'] = $this->mapStatusToComplete($validated['status']);
        $gsd->update($validated);
        return redirect()->route('gsd.index');
    }

    public function destroy(Gsd $gsd)
    {
        $gsd->delete();
        return redirect()->route('gsd.index');
    }

    public function show(Gsd $gsd)
    {
        return view('gsd.show', compact('gsd'));
    }

    public function edit(Gsd $gsd)
    {
        return view('gsd.edit', compact('gsd'));
    }

    private function validateInput(Request $request)
    {
        return $request->validate([
            'event' => 'required|max:255',
            'unit' => 'nullable|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'uic' => 'nullable|max:255',
            'unit_collab' => 'nullable|max:255',
            'status' => 'nullable|max:255',
            'respond' => 'nullable|string'
        ]);
    }

    private function mapStatusToComplete($status)
    {
        return [
            'open' => 0,
            'need discuss' => 25,
            'eskalasi' => 50,
            'progress' => 75,
            'done' => 100,
        ][strtolower($status)] ?? 0;
    }
}
