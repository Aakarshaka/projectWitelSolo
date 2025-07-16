<?php

namespace App\Http\Controllers;

use App\Models\Snam;
use App\Models\Gsd;
use App\Models\Tsel;
use App\Models\Treg;
use App\Models\Tifta;
use App\Models\Witel;
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
        $validatedData = $this->validateInput($request);
        $validatedData['complete'] = $this->mapStatusToComplete($validatedData['status']);

        $data = Snam::create($validatedData);

        if (strtolower($validatedData['status']) === 'eskalasi') {
            $this->dispatchEskalasi($data);
        }

        return redirect()->route('snam.index');
    }

    public function update(Request $request, Snam $snam)
    {
        $validatedData = $this->validateInput($request);
        $validatedData['complete'] = $this->mapStatusToComplete($validatedData['status']);

        $snam->update($validatedData);

        if (strtolower($validatedData['status']) === 'eskalasi') {
            $this->syncEskalasi($snam);
        }

        return redirect()->route('snam.index');
    }

    public function destroy(Snam $snam)
    {
        $snam->delete();
        return redirect()->route('snam.index');
    }

    public function show(Snam $snam)
    {
        return view('snam.show', compact('snam'));
    }

    public function edit(Snam $snam)
    {
        return view('snam.edit', compact('snam'));
    }

    private function validateInput(Request $request)
    {
        return $request->validate([
            'event' => 'required|max:255',
            'unit' => 'nullable|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'uic' => 'nullable|max:255',
            'unit_collab' => 'nullable|max:255',
            'status' => 'nullable|max:255',
            'respond' => 'nullable|string',
        ]);
    }

    private function mapStatusToComplete($status)
    {
        $map = [
            'open' => 0,
            'need discuss' => 25,
            'eskalasi' => 50,
            'progress' => 75,
            'done' => 100,
        ];
        return $map[strtolower($status)] ?? 0;
    }

    private function dispatchEskalasi($data)
    {
        $uic = strtoupper($data->uic ?? $data->unit_collab);
        $payload = $data->toArray();
        unset($payload['id'], $payload['created_at'], $payload['updated_at']);
        $payload['snam_id'] = $data->id;

        $toWitel = ['BS', 'GS', 'RSO WITEL', 'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ'];
        $toTreg  = ['RSMES', 'RLEGS', 'BPPLP', 'RSO', 'SSS'];

        if (in_array($uic, $toWitel)) Witel::create($payload);
        if (in_array($uic, $toTreg))  Treg::create($payload);
        if ($uic === 'TIF_TA')        Tifta::create($payload);
        if ($uic === 'TSEL')          Tsel::create($payload);
        if ($uic === 'GSD')           Gsd::create($payload);
    }

    private function syncEskalasi($data)
    {
        $uic = strtoupper($data->uic ?? $data->unit_collab);
        $payload = $data->toArray();
        unset($payload['id'], $payload['created_at'], $payload['updated_at']);
        $key = ['snam_id' => $data->id];

        $toWitel = ['BS', 'GS', 'RSO WITEL', 'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ'];
        $toTreg  = ['RSMES', 'RLEGS', 'BPPLP', 'RSO', 'SSS'];

        if (in_array($uic, $toWitel)) Witel::updateOrCreate($key, $payload);
        if (in_array($uic, $toTreg))  Treg::updateOrCreate($key, $payload);
        if ($uic === 'TIF_TA')        Tifta::updateOrCreate($key, $payload);
        if ($uic === 'TSEL')          Tsel::updateOrCreate($key, $payload);
        if ($uic === 'GSD')           Gsd::updateOrCreate($key, $payload);
    }
}
