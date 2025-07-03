<?php

namespace App\Http\Controllers;

use App\Models\gsd;
use Illuminate\Http\Request;

class GsdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allgsd = gsd::all();
        return view('eskalasi.gsd', compact('allgsd'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gsd.create');
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
        gsd::create($validatedData);

        //redirect
        return redirect()->route('gsd.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(gsd $gsd)
    {
        return view('gsd.show',compact('gsd'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(gsd $gsd)
    {
        return view('gsd.edit',compact('gsd'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, gsd $gsd)
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
        $gsd->update($validatedData);

        //redirect
        return redirect()->route('gsd.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(gsd $gsd)
    {
        $gsd->delete();
        return redirect()->route('gsd.index');
    }
}
