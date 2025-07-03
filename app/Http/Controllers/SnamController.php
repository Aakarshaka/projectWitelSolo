<?php

namespace App\Http\Controllers;

use App\Models\snam;
use Illuminate\Http\Request;

class SnamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allsnam = snam::all();
        return view('supportNeeded.snam', compact('allsnam'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('form.create');
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
        snam::create($validatedData);

        //redirect
        return redirect()->route('snam.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(snam $snam)
    {
        return view('snam.show',compact('snam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(snam $snam)
    {
        return view('snam.edit',compact('snam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, snam $snam)
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
        $snam->update($validatedData);

        //redirect
        return redirect()->route('snam.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(snam $snam)
    {
        $snam->delete();
        return redirect()->route('eskalasi.snam');
    }
}
