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
        $allsnam = Snam::all();

        $total = $allsnam->count();

        $close = $allsnam->where('status', 'Done')->count();

        $open = $total - $close;

        $closePercentage = $open > 0 ? round(($close / $total) * 100, 1) : 0;

        $actualProgress = $allsnam->avg('complete');  // Laravel Collection method

        return view('supportNeeded.snam', compact('allsnam', 'total', 'close', 'closePercentage', 'actualProgress'));
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
        return redirect()->route('snam.index');
    }
}
