<?php

namespace App\Http\Controllers;

use App\Models\{Snam, Gsd, Tsel, Treg, Tifta, Witel};
use Illuminate\Http\Request;

class SnamController extends Controller
{
    public function index()
    {
        $allsnam = Snam::all();

        $total = $allsnam->count();
        $close = $allsnam->where('status', 'Done')->count();
        $open = $total - $close;
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;
        $actualProgress = $allsnam->avg('complete');

        return view('supportNeeded.snam', compact('allsnam', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    public function create()
    {
        return view('snam.create');
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

        $data = Snam::create($validatedData);

        if ($validatedData['status'] === 'Eskalasi') {
            $this->dispatchEskalasi($data);
        }

        return redirect()->route('snam.index');
    }

    private function dispatchEskalasi($data)
    {
        $uic = strtoupper($data->uic ?? $data->unit_collab);
        $payload = $data->toArray();
        unset($payload['id']);
        $payload['snam_id'] = $data->id;

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

        if (in_array($uic, $toWitel)) Witel::updateOrCreate(['snam_id' => $id], $payload);
        if (in_array($uic, $toTreg)) Treg::updateOrCreate(['snam_id' => $id], $payload);
        if ($uic === 'TIF_TA') Tifta::updateOrCreate(['snam_id' => $id], $payload);
        if ($uic === 'TSEL') Tsel::updateOrCreate(['snam_id' => $id], $payload);
        if ($uic === 'GSD') Gsd::updateOrCreate(['snam_id' => $id], $payload);
    }

    public function edit(Snam $snam)
    {
        return view('snam.edit', compact('snam'));
    }

    public function update(Request $request, Snam $snam)
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

        $snam->update($validatedData);

        if ($validatedData['status'] === 'Eskalasi') {
            $this->syncEskalasi($snam);
        }

        return redirect()->route('snam.index');
    }

    public function destroy(Snam $snam)
    {
        $snam->delete();
        return redirect()->route('snam.index');
    }
}

// Untuk SnunitController, tinggal ganti semua `Snam` menjadi `Snunit`, dan tambahkan use `use App\Models\Snunit;`
