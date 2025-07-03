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

        $actualProgress = $allsntelda->avg('complete');  // Laravel Collection method

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
        // validate
        $validatedData = $request->validate([
            'event' => 'required|max:255',
            'unit' => 'nullable|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'uic' => 'nullable|max:255',
            'unit_collab' => 'nullable|max:255',
            'complete' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|max:255',
            'respond' => 'nullable|string'
        ]);

        $validatedData['status'] = $validatedData['status'] ?? null;

        // Validasi kalau complete = 100, status harus Done
        if ($validatedData['complete'] == 100 && $validatedData['status'] != 'Done') {
            return back()->withErrors(['status' => 'Jika progress 100%, status harus Done'])->withInput();
        }

        // Validasi kalau complete = 0, status harus kosong/null
        if ($validatedData['complete'] == 0 && $validatedData['status'] != null) {
            return back()->withErrors(['status' => 'Jika progress 0%, status wajib kosong.'])->withInput();
        }


        //simpan
        sntelda::create($validatedData);

        //redirect
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
        // validate
        $validatedData = $request->validate([
            'event' => 'required|max:255',
            'unit' => 'nullable|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'uic' => 'nullable|max:255',
            'unit_collab' => 'nullable|max:255',
            'complete' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|max:255',
            'respond' => 'nullable|string'
        ]);

        // Validasi kalau complete = 100, status harus Done
        if ($validatedData['complete'] == 100 && $validatedData['status'] != 'Done') {
            return back()->withErrors(['status' => 'Jika progress 100%, status harus Done'])->withInput();
        }

        // Validasi kalau complete = 0, status harus kosong/null
        if ($validatedData['complete'] == 0 && $validatedData['status'] != null) {
            return back()->withErrors(['status' => 'Jika progress 0%, status wajib kosong.'])->withInput();
        }

        $validatedData['status'] = $validatedData['status'] ?? null;

        //simpan
        $sntelda->update($validatedData);

        //redirect
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
