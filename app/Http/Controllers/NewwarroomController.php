<?php

namespace App\Http\Controllers;


use App\Models\supportneeded;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\newwarroom;


class NewwarroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warroomData = Newwarroom::all();
        return view('warroom.newwarroom', compact('warroomData'));
    }

    public function syncFromSupportneeded(): RedirectResponse
    {
        $data = Supportneeded::where('status', 'Action')->get();

        foreach ($data as $item) {
            Newwarroom::create([
                'tgl' => $item->start_date,
                'agenda' => $item->agenda,
                'uic' => $item->uic,
                // kosongkan dulu
                // kolom lainnya dibiarkan null juga
            ]);
        }

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil disalin dari Supportneeded.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'nullable|date',
            'agenda' => 'nullable|string',
            'uic' => 'nullable|string',
            'peserta' => 'nullable|string',
            'pembahasan' => 'nullable|string',
            'action_plan' => 'nullable|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'nullable|integer',
            'update_action_plan' => 'nullable|string',
            'status_action_plan' => 'nullable|string',
        ]);

        Newwarroom::create($validated);

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(newwarroom $newwarroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Edit
    public function edit(Newwarroom $newwarroom)
    {
        return view('newwarroom.edit', ['data' => $newwarroom]);
    }

    // Update
    public function update(Request $request, Newwarroom $newwarroom)
    {
        $validated = $request->validate([
            'tgl' => 'nullable|date',
            'agenda' => 'nullable|string',
            'uic' => 'nullable|string',
            'peserta' => 'nullable|string',
            'pembahasan' => 'nullable|string',
            'action_plan' => 'nullable|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'nullable|integer',
            'update_action_plan' => 'nullable|string',
            'status_action_plan' => 'nullable|string',
        ]);

        $newwarroom->update($validated);

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil diperbarui.');
    }

    // Destroy
    public function destroy(Newwarroom $newwarroom)
    {
        $newwarroom->delete();

        return redirect()->route('newwarroom.index')->with('success', 'Data berhasil dihapus.');
    }

}
