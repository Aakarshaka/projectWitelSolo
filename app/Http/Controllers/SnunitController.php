<?php

namespace App\Http\Controllers;

use App\Models\{Snunit, Gsd, Tsel, Treg, Tifta, Witel};
use Illuminate\Http\Request;

class SnunitController extends Controller
{
    public function index()
    {
        $allsnunit = Snunit::all();

        $total = $allsnunit->count();
        $close = $allsnunit->where('status', 'Done')->count();
        $open = $total - $close;
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;
        $actualProgress = $allsnunit->avg('complete');

        return view('supportNeeded.snunit', compact('allsnunit', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    public function create()
    {
        return view('snunit.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'event' => 'required|max:255',
            'unit' => 'nullable|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'uic' => 'nullable|max:255',
            'unit_collab' => 'nullable|max:255',
            'status' => 'nullable|max:255',
            'respond' => 'nullable|string'
        ]);

        $statusCompleteMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'Eskalasi' => 50,
            'Progress' => 75,
            'Done' => 100
        ];

        $validatedData['complete'] = $statusCompleteMap[$validatedData['status']] ?? 0;

        $data = Snunit::create($validatedData);

        if ($validatedData['status'] === 'Eskalasi') {
            $this->dispatchEskalasi($data);
        }

        return redirect()->route('snunit.index');
    }

    private function dispatchEskalasi($data)
    {
        $uic = strtoupper($data->uic ?? $data->unit_collab);
        $payload = $data->toArray();
        unset($payload['id']);
        $payload['snunit_id'] = $data->id;

        $toWitel = ['BS', 'GS', 'RLEGS', 'RSO', 'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ'];
        $toTreg = ['RSMES', 'RLEGS', 'BPPLP', 'RSO', 'SSS'];

        if (in_array($uic, $toWitel)) Witel::create($payload);
        if (in_array($uic, $toTreg)) Treg::create($payload);
        if ($uic === 'TIF_TA') Tifta::create($payload);
        if ($uic === 'TSEL') Tsel::create($payload);
        if ($uic === 'GSD') Gsd::create($payload);
    }

    private function syncEskalasi($data)
    {
        $uic = strtoupper($data->uic ?? $data->unit_collab);
        $payload = $data->toArray();
        $id = $data->id;

        $toWitel = ['BS', 'GS', 'RLEGS', 'RSO', 'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ'];
        $toTreg = ['RSMES', 'RLEGS', 'BPPLP', 'RSO', 'SSS'];

        if (in_array($uic, $toWitel)) Witel::updateOrCreate(['snunit_id' => $id], $payload);
        if (in_array($uic, $toTreg)) Treg::updateOrCreate(['snunit_id' => $id], $payload);
        if ($uic === 'TIF_TA') Tifta::updateOrCreate(['snunit_id' => $id], $payload);
        if ($uic === 'TSEL') Tsel::updateOrCreate(['snunit_id' => $id], $payload);
        if ($uic === 'GSD') Gsd::updateOrCreate(['snunit_id' => $id], $payload);
    }

    public function edit(Snunit $snunit)
    {
        return view('snunit.edit', compact('snunit'));
    }

    public function update(Request $request, Snunit $snunit)
    {
        $validatedData = $request->validate([
            'event' => 'required|max:255',
            'unit' => 'nullable|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'uic' => 'nullable|max:255',
            'unit_collab' => 'nullable|max:255',
            'status' => 'nullable|max:255',
            'respond' => 'nullable|string'
        ]);

        $statusCompleteMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'Eskalasi' => 50,
            'Progress' => 75,
            'Done' => 100
        ];

        $validatedData['complete'] = $statusCompleteMap[$validatedData['status']] ?? 0;

        $snunit->update($validatedData);

        if ($validatedData['status'] === 'Eskalasi') {
            $this->syncEskalasi($snunit);
        }

        return redirect()->route('snunit.index');
    }

    public function destroy(Snunit $snunit)
    {
        $snunit->delete();
        return redirect()->route('snunit.index');
    }
}

// Untuk SnunitController, tinggal ganti semua `Snam` menjadi `Snunit`, dan tambahkan use `use App\Models\Snunit;`
