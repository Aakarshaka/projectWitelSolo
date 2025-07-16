<?php

namespace App\Http\Controllers;

use App\Models\Witel;
use Illuminate\Http\Request;

class WitelController extends Controller
{
    public function index()
    {
        $allwitel = Witel::all();
        $total = $allwitel->count();
        $close = $allwitel->where('status', 'Done')->count();
        $open = $total - $close;
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;
        $actualProgress = $allwitel->avg('complete');

        return view('eskalasi.witel', compact('allwitel', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    public function create()
    {
        return view('witel.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        $validated['complete'] = $this->mapStatusToComplete($validated['status']);
        Witel::create($validated);
        return redirect()->route('witel.index');
    }

    public function update(Request $request, Witel $witel)
    {
        $validated = $this->validateInput($request);
        $validated['complete'] = $this->mapStatusToComplete($validated['status']);
        $witel->update($validated);
        return redirect()->route('witel.index');
    }

    public function destroy(Witel $witel)
    {
        $witel->delete();
        return redirect()->route('witel.index');
    }

    public function show(Witel $witel)
    {
        return view('witel.show', compact('witel'));
    }

    public function edit(Witel $witel)
    {
        return view('witel.edit', compact('witel'));
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
