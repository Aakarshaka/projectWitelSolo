<?php

namespace App\Http\Controllers;

use App\Models\Sntelda;
use App\Models\Gsd;
use App\Models\Tsel;
use App\Models\Treg;
use App\Models\Tifta;
use App\Models\Witel;
use Illuminate\Http\Request;

class SnteldaController extends Controller
{
    public function index()
    {
        $allsntelda = Sntelda::all();

        $total = $allsntelda->count();
        $close = $allsntelda->where('status', 'Done')->count();
        $open = $total - $close;
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;
        $actualProgress = $allsntelda->avg('complete');

        return view('supportNeeded.sntelda', compact('allsntelda', 'total', 'close', 'closePercentage', 'actualProgress'));
    }

    public function create()
    {
        return view('sntelda.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateInput($request);

        // Mapping nilai progress dari status
        $validatedData['complete'] = $this->mapStatusToComplete($validatedData['status']);

        $data = Sntelda::create($validatedData);

        if (strtolower($validatedData['status']) === 'eskalasi') {
            $this->dispatchEskalasi($data);
        }

        return redirect()->route('sntelda.index');
    }

    public function update(Request $request, Sntelda $sntelda)
    {
        $validatedData = $this->validateInput($request);
        $validatedData['complete'] = $this->mapStatusToComplete($validatedData['status']);

        $sntelda->update($validatedData);

        if (strtolower($validatedData['status']) === 'eskalasi') {
            $this->syncEskalasi($sntelda);
        }

        return redirect()->route('sntelda.index');
    }

    public function destroy(Sntelda $sntelda)
    {
        $sntelda->delete();
        return redirect()->route('sntelda.index');
    }

    public function show(Sntelda $sntelda)
    {
        return view('sntelda.show', compact('sntelda'));
    }

    public function edit(Sntelda $sntelda)
    {
        return view('sntelda.edit', compact('sntelda'));
    }

    /** -----------------------------
     *  Helper Functions
     *  ----------------------------- */

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
        $payload['sntelda_id'] = $data->id;

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
        $key = ['sntelda_id' => $data->id];

        $toWitel = ['BS', 'GS', 'RSO WITEL', 'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ'];
        $toTreg  = ['RSMES', 'RLEGS', 'BPPLP', 'RSO', 'SSS'];

        if (in_array($uic, $toWitel)) Witel::updateOrCreate($key, $payload);
        if (in_array($uic, $toTreg))  Treg::updateOrCreate($key, $payload);
        if ($uic === 'TIF_TA')        Tifta::updateOrCreate($key, $payload);
        if ($uic === 'TSEL')          Tsel::updateOrCreate($key, $payload);
        if ($uic === 'GSD')           Gsd::updateOrCreate($key, $payload);
    }
}
