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
        $allsntelda = sntelda::all();
        return view('supportNeeded.sntelda', compact('allsntelda'));
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
             'Event' => 'required|max:255',
        ]);

        //simpan
        sntelda::create($validatedData);

        //redirect
        return redirect()->route('supportNeeded.sntelda');
    }

    /**
     * Display the specified resource.
     */
    public function show(sntelda $sntelda)
    {
        return view('sntelda.show',compact('sntelda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sntelda $sntelda)
    {
        return view('sntelda.edit',compact('sntelda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sntelda $sntelda)
    {
        // validate
        $validatedData = $request->validate([
             'Event' => 'required|max:255',
        ]);

        //simpan
        $sntelda->update($validatedData);

        //redirect
        return redirect()->route('supportNeeded.sntelda');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sntelda $sntelda)
    {
        $sntelda->delete();
        return redirect()->route('supportNeeded.sntelda');
    }
}
