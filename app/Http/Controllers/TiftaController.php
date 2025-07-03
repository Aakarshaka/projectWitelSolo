<?php

namespace App\Http\Controllers;

use App\Models\tifta;
use Illuminate\Http\Request;

class TiftaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alltifta = tifta::all();
        return view('eskalasi.tifta', compact('alltifta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tifta.create');
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
        tifta::create($validatedData);

        //redirect
        return redirect()->route('tifta.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(tifta $tifta)
    {
        return view('tifta.show',compact('tifta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tifta $tifta)
    {
        return view('tifta.edit',compact('tifta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tifta $tifta)
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
        $tifta->update($validatedData);

        //redirect
        return redirect()->route('tifta.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tifta $tifta)
    {
        $tifta->delete();
        return redirect()->route('eskalasi.tifta');
    }
}
