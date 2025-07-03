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
        $allgsd = Gsd::all();

        $total = $allgsd->count();

        $close = $allgsd->where('status', 'Done')->count();

        $open = $total - $close;

        $closePercentage = $open > 0 ? round(($close / $total) * 100, 1) : 0;

        $actualProgress = $allgsd->avg('complete');  // Laravel Collection method

        return view('eskalasi.gsd', compact('allgsd', 'total', 'close', 'closePercentage', 'actualProgress'));
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
