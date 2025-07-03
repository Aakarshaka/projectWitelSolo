<?php

namespace App\Http\Controllers;

use App\Models\tsel;
use Illuminate\Http\Request;

class TselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alltsel = tsel::all();
        return view('eskalasi.tsel', compact('alltsel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tsel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate
        $validatedData = $request->validate([
            'event' => 'required|max:255',
            'unit' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'uic' => 'required|max:255',
            'unit_collab' => 'nullable|max:255',
            'complete' => 'required|integer|min:0|max:100',
            'status' => 'required|max:255',
            'respond' => 'nullable|string'
        ]);

        //simpan
        tsel::create($validatedData);

        //redirect
        return redirect()->route('tsel.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(tsel $tsel)
    {
        return view('tsel.show',compact('tsel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tsel $tsel)
    {
        return view('tsel.edit',compact('tsel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tsel $tsel)
    {
        // validate
        $validatedData = $request->validate([
            'event' => 'required|max:255',
            'unit' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'uic' => 'required|max:255',
            'unit_collab' => 'nullable|max:255',
            'complete' => 'required|integer|min:0|max:100',
            'status' => 'required|max:255',
            'respond' => 'nullable|string'
        ]);

        //simpan
        $tsel->update($validatedData);

        //redirect
        return redirect()->route('tsel.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tsel $tsel)
    {
        $tsel->delete();
        return redirect()->route('eskalasi.tsel');
    }
}
