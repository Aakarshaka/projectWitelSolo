<?php

namespace App\Http\Controllers;

use App\Models\warroom;
use Illuminate\Http\Request;

class WarroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allwarroom = Warroom::all();
        // Ambil semua data activity
        $activities = Warroom::orderBy('tgl')->get();

        // Ambil data summary (diolah dari data di atas)
        $jumlahAgenda = $activities->pluck('agenda')->unique()->count();
        $namaAgenda = $activities->pluck('agenda')->unique();
        $jumlahActionPlan = $activities->sum('jumlah_action_plan');
        $jumlahEskalasi = $activities->where('status_action_plan', 'like', '%eskalasi%')->count(); // jika ada keyword eskalasi

        return view('warroom', compact('allwarroom', 'activities', 'jumlahAgenda', 'namaAgenda', 'jumlahActionPlan', 'jumlahEskalasi'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warroom.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi
        $validated = $request->validate([
            'tgl' => 'required|date',
            'agenda' => 'required|string',
            'peserta' => 'required|string',
            'pembahasan' => 'required|string',
            'action_plan' => 'required|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'required|integer',
            'update_action_plan' => 'nullable|string',
            'status_action_plan' => 'nullable|string',
        ]);

        //simpan
        warroom::create($validatedData);

        //redirect
        return redirect()->route('warroom.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(warroom $warroom)
    {
        return view('warroom.show',compact('warroom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(warroom $warroom)
    {
        return view('warroom.edit',compact('warroom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, warroom $warroom)
    {
        //validasi
        $validated = $request->validate([
            'tgl' => 'required|date',
            'agenda' => 'required|string',
            'peserta' => 'required|string',
            'pembahasan' => 'required|string',
            'action_plan' => 'required|string',
            'support_needed' => 'nullable|string',
            'info_kompetitor' => 'nullable|string',
            'jumlah_action_plan' => 'required|integer',
            'update_action_plan' => 'nullable|string',
            'status_action_plan' => 'nullable|string',
        ]);

        //simpan
        $warroom->update($validatedData);

        //redirect
        return redirect()->route('warroom.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(warroom $warroom)
    {
        $warroom->delete();
        return redirect()->route('warroom.index');
    }
}
