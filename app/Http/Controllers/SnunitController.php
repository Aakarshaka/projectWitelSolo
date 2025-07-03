<?php

namespace App\Http\Controllers;

use App\Models\snunit;
use Illuminate\Http\Request;

class SnunitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allsnunit = snunit::all();
        return view('supportNeeded.snunit', compact('allsnunit'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('snunit.create');
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
        snunit::create($validatedData);

        //redirect
        return redirect()->route('snunit.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(snunit $snunit)
    {
        return view('snunit.show',compact('snunit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(snunit $snunit)
    {
        return view('snunit.edit',compact('snunit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, snunit $snunit)
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
        $snunit->update($validatedData);

        //redirect
        return redirect()->route('snunit.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(snunit $snunit)
    {
        $snunit->delete();
        return redirect()->route('snunit.index');
    }
}
