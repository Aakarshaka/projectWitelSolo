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
        $alltifta = Tifta::all();

        $total = $alltifta->count();

        $close = $alltifta->where('status', 'Done')->count();

        $open = $total - $close;

        $closePercentage = $open > 0 ? round(($close / $total) * 100, 1) : 0;

        $actualProgress = $alltifta->avg('complete');  // Laravel Collection method

        return view('eskalasi.tifta', compact('alltifta', 'total', 'close', 'closePercentage', 'actualProgress'));
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
        return redirect()->route('tifta.index');
    }
}
