<?php

namespace App\Http\Controllers;

use App\Models\treg;
use Illuminate\Http\Request;

class TregController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alltreg = treg::all();
        return view('eskalasi.treg', compact('alltreg'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('treg.create');
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
        treg::create($validatedData);

        //redirect
        return redirect()->route('treg.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(treg $treg)
    {
        return view('treg.show',compact('treg'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(treg $treg)
    {
        return view('treg.edit',compact('treg'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, treg $treg)
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
        $treg->update($validatedData);

        //redirect
        return redirect()->route('treg.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(treg $treg)
    {
        $treg->delete();
        return redirect()->route('eskalasi.treg');
    }
}
