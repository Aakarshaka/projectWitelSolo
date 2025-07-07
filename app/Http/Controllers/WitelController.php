<?php

namespace App\Http\Controllers;

use App\Models\witel;
use Illuminate\Http\Request;

class WitelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allwitel = witel::all();

        $total = $allwitel->count();

        $close = $allwitel->where('status', 'Done')->count();

        $open = $total - $close;

        $closePercentage = $open > 0 ? round(($close / $total) * 100, 1) : 0;

        $actualProgress = $allwitel->avg('complete');  // Laravel Collection method

        return view('eskalasi.tsel', compact('allwitel', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('witel.create');
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
        witel::create($validatedData);

        //redirect
        return redirect()->route('witel.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(witel $witel)
    {
        return view('witel.show',compact('witel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(witel $witel)
    {
        return view('witel.edit',compact('witel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, witel $witel)
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
        $witel->update($validatedData);

        //redirect
        return redirect()->route('witel.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(witel $witel)
    {
        $witel->delete();
        return redirect()->route('witel.index');
    }
}
