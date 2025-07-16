<?php

namespace App\Http\Controllers;

use App\Models\Tifta;
use Illuminate\Http\Request;

class TiftaController extends Controller
{
    public function index()
    {
        $alltifta = Tifta::all();
        $total = $alltifta->count();
        $close = $alltifta->where('status', 'Done')->count();
        $open = $total - $close;
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;
        $actualProgress = $alltifta->avg('complete');

        return view('eskalasi.tifta', compact('alltifta', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    public function create()
    {
        return view('tifta.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        $validated['complete'] = $this->mapStatusToComplete($validated['status']);
        Tifta::create($validated);
        return redirect()->route('tifta.index');
    }

    public function update(Request $request, Tifta $tifta)
    {
        $validated = $this->validateInput($request);
        $validated['complete'] = $this->mapStatusToComplete($validated['status']);
        $tifta->update($validated);
        return redirect()->route('tifta.index');
    }

    public function destroy(Tifta $tifta)
    {
        $tifta->delete();
        return redirect()->route('tifta.index');
    }

    public function show(Tifta $tifta)
    {
        return view('tifta.show', compact('tifta'));
    }

    public function edit(Tifta $tifta)
    {
        return view('tifta.edit', compact('tifta'));
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
