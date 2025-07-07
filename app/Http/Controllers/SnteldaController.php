<?php

namespace App\Http\Controllers;

use App\Models\sntelda;
use Illuminate\Http\Request;

class SnteldaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allsntelda = Sntelda::all();

        $total = $allsntelda->count();
        $close = $allsntelda->where('status', 'Done')->count();
        $open = $total - $close;
        $closePercentage = $open > 0 ? round(($close / $total) * 100, 1) : 0;
        $actualProgress = $allsntelda->avg('complete');

        return view('supportNeeded.sntelda', compact('allsntelda', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('form.createtelda');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi input
        $validatedData = $request->validate([
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

        // mapping nilai complete berdasarkan status
        $statusCompleteMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'Eskalasi' => 50,
            'Progress' => 75,
            'Done' => 100
        ];

        $validatedData['complete'] = $statusCompleteMap[$validatedData['status']] ?? 0;

        // simpan ke database
        Sntelda::create($validatedData);

        return redirect()->route('sntelda.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(sntelda $sntelda)
    {
        return view('sntelda.show', compact('sntelda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sntelda $sntelda)
    {
        return view('sntelda.edit', compact('sntelda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sntelda $sntelda)
    {
        // validasi input
        $validatedData = $request->validate([
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

        // mapping nilai complete berdasarkan status
        $statusCompleteMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'Eskalasi' => 50,
            'Progress' => 75,
            'Done' => 100
        ];

        $validatedData['complete'] = $statusCompleteMap[$validatedData['status']] ?? 0;

        // update data ke database
        $sntelda->update($validatedData);

        return redirect()->route('sntelda.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sntelda $sntelda)
    {
        $sntelda->delete();
        return redirect()->route('sntelda.index');
    }
}
