<?php

namespace App\Http\Controllers;

use App\Models\Supportneeded;
use Illuminate\Http\Request;

class SupportneededController extends Controller
{
    public function index(Request $request)
    {
        $query = Supportneeded::query();

        if ($request->type_agenda) {
            $query->where('agenda', $request->type_agenda);
        }

        if ($request->unit_or_telda) {
            $query->where('unit_or_telda', $request->unit_or_telda);
        }

        if ($request->uic) {
            $query->where('uic', $request->uic);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('agenda', 'like', '%' . $request->search . '%')
                    ->orWhere('unit_or_telda', 'like', '%' . $request->search . '%')
                    ->orWhere('notes_to_follow_up', 'like', '%' . $request->search . '%')
                    ->orWhere('uic', 'like', '%' . $request->search . '%')
                    ->orWhere('progress', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%')
                    ->orWhere('response_uic', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->paginate(10);

        $allItems = Supportneeded::all();
        $total = $allItems->count();
        $close = $allItems->where('progress', 'Done')->count();
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;

        $progressMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'Progress' => 75,
            'Done' => 100,
        ];

        $totalProgress = 0;
        $count = 0;

        foreach ($allItems as $item) {
            if (isset($progressMap[$item->progress])) {
                $totalProgress += $progressMap[$item->progress];
                $count++;
            }
        }

        $avgProgress = $count > 0 ? round($totalProgress / $count, 1) : 0;

        return view('supportneeded.supportneeded', compact(
            'items',
            'total',
            'close',
            'closePercentage',
            'avgProgress'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agenda' => 'required|string',
            'unit_or_telda' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'off_day' => 'nullable|integer',
            'notes_to_follow_up' => 'nullable|string',
            'uic' => 'nullable|string',
            'progress' => 'nullable|string',
            'complete' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|string',
            'response_uic' => 'nullable|string',
        ]);

        $escalationUics = ['RLEGS', 'RSO REGIONAL', 'ED', 'TIF', 'TSEL', 'GSD', 'RSMES', 'BPPLP', 'SSS'];
        $supportNeededUics = ['BS', 'GS', 'RSO WITEL', 'SSGS', 'PRQ'];

        if (empty($validated['unit_or_telda']) && empty($validated['uic'])) {
            $status = '';
        } elseif ($validated['unit_or_telda'] === $validated['uic']) {
            $status = 'Action';
        } elseif (in_array($validated['uic'], $escalationUics)) {
            $status = 'Eskalasi';
        } elseif (in_array($validated['uic'], $supportNeededUics)) {
            $status = 'Support Needed';
        } else {
            $status = '';
        }

        $validated['status'] = $status;

        // Hitung off_day
        $start = $validated['start_date'] ?? null;
        $end = $validated['end_date'] ?? null;
        $validated['off_day'] = ($start && $end)
            ? \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($end)) + 1
            : 0;

        // Hitung complete (berdasarkan progress jika kosong)
        if (!isset($validated['complete'])) {
            switch ($validated['progress']) {
                case 'Open':
                    $validated['complete'] = 0;
                    break;
                case 'Need Discuss':
                    $validated['complete'] = 25;
                    break;
                case 'Progress':
                    $validated['complete'] = 75;
                    break;
                case 'Done':
                    $validated['complete'] = 100;
                    break;
                default:
                    $validated['complete'] = 0;
            }
        }

        Supportneeded::create($validated);
        return back()->with('success', 'Agenda created');
    }

    public function update(Request $request, Supportneeded $supportneeded)
    {
        $validated = $request->validate([
            'agenda' => 'required|string',
            'unit_or_telda' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'off_day' => 'nullable|integer',
            'notes_to_follow_up' => 'nullable|string',
            'uic' => 'nullable|string',
            'progress' => 'nullable|string',
            'complete' => 'nullable|integer|min:0|max:100',
            'status' => 'nullable|string',
            'response_uic' => 'nullable|string',
        ]);

        $escalationUics = ['RLEGS', 'RSO REGIONAL', 'ED', 'TIF', 'TSEL', 'GSD', 'RSMES', 'BPPLP', 'SSS'];
        $supportNeededUics = ['BS', 'GS', 'RSO WITEL', 'SSGS', 'PRQ'];

        if (empty($validated['unit_or_telda']) && empty($validated['uic'])) {
            $status = '';
        } elseif ($validated['unit_or_telda'] === $validated['uic']) {
            $status = 'Action';
        } elseif (in_array($validated['uic'], $escalationUics)) {
            $status = 'Eskalasi';
        } elseif (in_array($validated['uic'], $supportNeededUics)) {
            $status = 'Support Needed';
        } else {
            $status = '';
        }

        $validated['status'] = $status;

        // Hitung off_day
        $start = $validated['start_date'] ?? null;
        $end = $validated['end_date'] ?? null;
        $validated['off_day'] = ($start && $end)
            ? \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($end)) + 1
            : 0;

        // Hitung complete (berdasarkan progress jika kosong)
        if (!isset($validated['complete'])) {
            switch ($validated['progress']) {
                case 'Open':
                    $validated['complete'] = 0;
                    break;
                case 'Need Discuss':
                    $validated['complete'] = 25;
                    break;
                case 'Progress':
                    $validated['complete'] = 75;
                    break;
                case 'Done':
                    $validated['complete'] = 100;
                    break;
                default:
                    $validated['complete'] = 0;
            }
        }

        $supportneeded->update($validated);
        return back()->with('success', 'Agenda updated');
    }

    public function destroy(Supportneeded $supportneeded)
    {
        $supportneeded->delete();
        return back()->with('success', 'Agenda deleted');
    }

    /**
     * Method to get detail data for popup by UIC and Progress
     */
    public function getDetail(Request $request)
    {
        $progress = $request->query('progress');
        $uic = $request->query('uic');
        $agenda = $request->query('agenda');
        $unit = $request->query('unit');

        if (!$progress) {
            return response()->json(['message' => 'Missing progress parameter'], 400);
        }

        if ($uic) {
            $data = Supportneeded::where('uic', $uic)
                ->where('progress', $progress)
                ->get();
        } elseif ($agenda) {
            $data = Supportneeded::where('agenda', $agenda)
                ->where('progress', $progress)
                ->get();
        } elseif ($unit) {
            $data = Supportneeded::where('unit_or_telda', $unit)
                ->where('progress', $progress)
                ->get();
        } else {
            return response()->json(['message' => 'Missing query parameter'], 400);
        }

        return response()->json($data);
    }

}
