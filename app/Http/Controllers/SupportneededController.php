<?php

namespace App\Http\Controllers;

use App\Models\Supportneeded;
use App\Models\newwarroom;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SupportneededController extends Controller
{
    public function index(Request $request)
    {
        $query = Supportneeded::query();

        if ($request->type_agenda) {
            $query->where('agenda', $request->type_agenda);
        }

        if ($request->progress) {
            $query->where('progress', $request->progress);
        }

        if ($request->status) {
            $query->where('status', $request->status);
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

        $items = $query->get();

        $allItems = Supportneeded::all();
        $total = $allItems->count();
        $close = $allItems->where('progress', 'Done')->count();
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;

        $progressMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'On Progress' => 75,
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

        if (empty($validated['unit_or_telda']) || empty($validated['uic'])) {
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

        // Logic untuk end_date dan off_day
        $start = $validated['start_date'] ?? null;

        // Jika progress = Done, set end_date ke hari ini
        if ($validated['progress'] === 'Done') {
            $validated['end_date'] = Carbon::now()->format('Y-m-d');
        }

        // Hitung off_day dinamis
        if ($start) {
            if ($validated['progress'] === 'Done' && $validated['end_date']) {
                // Jika Done, hitung dari start sampai end_date
                $validated['off_day'] = Carbon::parse($start)->diffInDays(Carbon::parse($validated['end_date'])) + 1;
            } else {
                // Jika belum Done, hitung dari start sampai hari ini
                $validated['off_day'] = Carbon::parse($start)->diffInDays(Carbon::now()) + 1;
            }
        } else {
            $validated['off_day'] = 0;
        }

        // Hitung complete (berdasarkan progress jika kosong)
        if (!isset($validated['complete'])) {
            switch ($validated['progress']) {
                case 'Open':
                    $validated['complete'] = 0;
                    break;
                case 'Need Discuss':
                    $validated['complete'] = 25;
                    break;
                case 'On Progress':
                    $validated['complete'] = 75;
                    break;
                case 'Done':
                    $validated['complete'] = 100;
                    break;
                default:
                    $validated['complete'] = 0;
            }
        }

        $support = Supportneeded::create($validated);

        log_activity('create', $support, 'Menambahkan data Support Needed');

        // Kalau status = Action → langsung buat di Newwarroom
        if ($support->status === 'Action') {
            $exists = Newwarroom::where('tgl', $support->start_date)
                ->where('agenda', $support->agenda)
                ->where('uic', $support->uic)
                ->exists();

            if (!$exists) {
                Newwarroom::create([
                    'tgl' => $support->start_date,
                    'agenda' => $support->agenda,
                    'uic' => $support->uic,
                ]);
            }
        }

        return redirect()->route('supportneeded.index')->with('success', 'Data berhasil disimpan.');
    }

    public function update(Request $request, Supportneeded $supportneeded)
    {
        $oldData = $supportneeded->toArray();

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

        if (empty($validated['unit_or_telda']) || empty($validated['uic'])) {
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

        // Logic untuk end_date dan off_day
        $start = $validated['start_date'] ?? null;

        // Jika progress berubah menjadi Done, set end_date ke hari ini
        if ($validated['progress'] === 'Done' && $supportneeded->progress !== 'Done') {
            $validated['end_date'] = Carbon::now()->format('Y-m-d');
        }
        // Jika progress berubah dari Done ke status lain, hapus end_date
        elseif ($validated['progress'] !== 'Done' && $supportneeded->progress === 'Done') {
            $validated['end_date'] = null;
        }

        // Hitung off_day dinamis
        if ($start) {
            if ($validated['progress'] === 'Done' && $validated['end_date']) {
                // Jika Done, hitung dari start sampai end_date
                $diffInHours = Carbon::parse($start)->diffInHours(Carbon::parse($validated['end_date']));
            } else {
                // Jika belum Done, hitung dari start sampai hari ini
                $diffInHours = Carbon::parse($start)->diffInHours(Carbon::now());
            }

            // Convert jam ke hari bilangan bulat
            $validated['off_day'] = ceil($diffInHours / 24);
        } else {
            $validated['off_day'] = 0;
        }

        // Hitung complete (berdasarkan progress jika kosong)
        if (!isset($validated['complete'])) {
            switch ($validated['progress']) {
                case 'Open':
                    $validated['complete'] = 0;
                    break;
                case 'Need Discuss':
                    $validated['complete'] = 25;
                    break;
                case 'On Progress':
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

        log_activity('update', $supportneeded, 'Memperbarui data Support Needed', [
            'before' => $oldData,
            'after' => $supportneeded->toArray(),
        ]);

        // Jika status berubah jadi Action, dan belum masuk ke warroom
        if ($validated['status'] === 'Action') {
            $exists = Newwarroom::where('tgl', $validated['start_date'])
                ->where('agenda', $validated['agenda'])
                ->where('uic', $validated['uic'])
                ->exists();

            if (!$exists) {
                Newwarroom::create([
                    'tgl' => $validated['start_date'],
                    'agenda' => $validated['agenda'],
                    'uic' => $validated['uic'],
                ]);
            }
        }

        return redirect()->route('supportneeded.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Supportneeded $supportneeded)
    {
        log_activity('delete', $supportneeded, 'Menghapus data Support Needed: ' . $supportneeded->agenda);
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
