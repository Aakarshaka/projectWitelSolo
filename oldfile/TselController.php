<?php

namespace App\Http\Controllers;

use App\Models\Tsel;
use Illuminate\Http\Request;

class TselController extends Controller
{
    public function index()
    {
        $alltsel = Tsel::all();
        $total = $alltsel->count();
        $close = $alltsel->where('status', 'Done')->count();
        $open = $total - $close;
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;
        $actualProgress = $alltsel->avg('complete');

        return view('eskalasi.tsel', compact('alltsel', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    public function create()
    {
        return view('tsel.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        $validated['complete'] = $this->mapStatusToComplete($validated['status']);
        Tsel::create($validated);
        return redirect()->route('tsel.index');
    }

    public function update(Request $request, Tsel $tsel)
    {
        $validated = $this->validateInput($request);
        $validated['complete'] = $this->mapStatusToComplete($validated['status']);
        $tsel->update($validated);
        return redirect()->route('tsel.index');
    }

    public function destroy(Tsel $tsel)
    {
        $tsel->delete();
        return redirect()->route('tsel.index');
    }

    public function show(Tsel $tsel)
    {
        return view('tsel.show', compact('tsel'));
    }

    public function edit(Tsel $tsel)
    {
        return view('tsel.edit', compact('tsel'));
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
