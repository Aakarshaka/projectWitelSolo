<?php

namespace App\Http\Controllers;

use App\Models\Treg;
use Illuminate\Http\Request;

class TregController extends Controller
{
    public function index()
    {
        $alltreg = Treg::all();
        $total = $alltreg->count();
        $close = $alltreg->where('status', 'Done')->count();
        $open = $total - $close;
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;
        $actualProgress = $alltreg->avg('complete');

        return view('eskalasi.treg', compact('alltreg', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    public function create()
    {
        return view('treg.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateInput($request);
        $validatedData['complete'] = $this->mapStatusToComplete($validatedData['status']);
        Treg::create($validatedData);

        return redirect()->route('treg.index');
    }

    public function update(Request $request, Treg $treg)
    {
        $validatedData = $this->validateInput($request);
        $validatedData['complete'] = $this->mapStatusToComplete($validatedData['status']);
        $treg->update($validatedData);

        return redirect()->route('treg.index');
    }

    public function destroy(Treg $treg)
    {
        $treg->delete();
        return redirect()->route('treg.index');
    }

    public function show(Treg $treg)
    {
        return view('treg.show', compact('treg'));
    }

    public function edit(Treg $treg)
    {
        return view('treg.edit', compact('treg'));
    }

    /** -----------------------------
     * Helper Methods
     * ----------------------------- */
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
        $map = [
            'open' => 0,
            'need discuss' => 25,
            'eskalasi' => 50,
            'progress' => 75,
            'done' => 100,
        ];
        return $map[strtolower($status)] ?? 0;
    }
}
