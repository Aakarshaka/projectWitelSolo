<?php

namespace App\Http\Controllers;

use App\Models\Supportneeded;
use App\Models\Newwarroom;
use Illuminate\Http\Request;

class SupportneededController extends Controller
{
    public function index(Request $request)
    {
        $query = Supportneeded::query();

        // Apply filters
        $this->applyFilters($query, $request);

        $supportneeded = $query->orderByRaw('start_date IS NULL')
            ->orderBy('start_date', 'asc')
            ->get();

        $items = $query->get();

        // Buat query baru dengan filter yang sama untuk statistik
        $statsQuery = Supportneeded::query();
        $this->applyFilters($statsQuery, $request);
        
        // Calculate statistics berdasarkan data yang sudah difilter
        $statistics = $this->calculateStatistics($statsQuery);

        return view('supportneeded.supportneeded', array_merge(
            compact('items'),
            $statistics
        ));
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, Request $request)
    {
        // ✅ Tambahkan 'bulan' dan 'tahun' ke dalam array filters
        $filters = ['progress', 'status', 'unit_or_telda', 'uic'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->$filter);
            }
        }

        // ✅ Filter berdasarkan bulan dan tahun
        if ($request->filled('bulan')) {
            $query->whereMonth('start_date', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('start_date', $request->tahun);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }
    }

    /**
     * Calculate dashboard statistics
     * ✅ PERBAIKAN: Terima query parameter untuk menghitung statistik berdasarkan data yang difilter
     */
    private function calculateStatistics($query = null)
    {
        // Jika tidak ada query yang dikirim, gunakan semua data (untuk backward compatibility)
        if ($query === null) {
            $allItems = Supportneeded::all();
        } else {
            // Gunakan query yang sudah difilter
            $allItems = $query->get();
        }

        $total = $allItems->count();
        $close = $allItems->where('progress', 'Done')->count();
        $closePercentage = $total > 0 ? round(($close / $total) * 100, 1) : 0;

        $totalProgress = 0;
        $count = 0;

        foreach ($allItems as $item) {
            if (isset(Supportneeded::PROGRESS_MAP[$item->progress])) {
                $totalProgress += Supportneeded::PROGRESS_MAP[$item->progress];
                $count++;
            }
        }

        $avgProgress = $count > 0 ? round($totalProgress / $count, 1) : 0;

        return compact('total', 'close', 'closePercentage', 'avgProgress');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $support = Supportneeded::create($validated);

        log_activity('create', $support, 'Menambahkan data Support Needed');

        // Sync with warroom
        $this->syncToWarroom($support);

        $filterParams = $this->getOriginalFilterParams($request);

        return redirect()->route('supportneeded.index', $filterParams)
            ->with('success', 'Data berhasil disimpan.');
    }

    public function update(Request $request, Supportneeded $supportneeded)
    {
        $oldData = $supportneeded->toArray();
        $validated = $this->validateRequest($request);

        $supportneeded->update($validated);

        log_activity('update', $supportneeded, 'Memperbarui data Support Needed', [
            'before' => $oldData,
            'after' => $supportneeded->toArray(),
        ]);

        // Sync with warroom
        $this->syncToWarroom($supportneeded);

        // ✅ Preserve filters from original request, bukan dari form data
        $filterParams = $this->getOriginalFilterParams($request);

        return redirect()->route('supportneeded.index', $filterParams)
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Supportneeded $supportneeded, Request $request)
    {
        log_activity('delete', $supportneeded, 'Menghapus data Support Needed: ' . $supportneeded->agenda);

        // Delete related warroom data
        Newwarroom::where('supportneeded_id', $supportneeded->id)->delete();

        $supportneeded->delete();

        // ✅ PERBAIKAN: Gunakan getOriginalFilterParams() yang sama dengan store dan update
        // untuk memastikan filter yang dipertahankan adalah filter dari URL
        $filterParams = $this->getOriginalFilterParams($request);

        return redirect()->route('supportneeded.index', $filterParams)
            ->with('success', 'Agenda deleted');
    }

    /**
     * Get filter parameters to preserve them during redirect
     */
    private function getFilterParams(Request $request)
    {
        $filterParams = [];
        // ✅ Tambahkan 'bulan' dan 'tahun' ke dalam array filters
        $filters = ['progress', 'status', 'unit_or_telda', 'uic', 'search', 'bulan', 'tahun'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $filterParams[$filter] = $request->$filter;
            }
        }

        return $filterParams;
    }

    /**
     * ✅ Method baru untuk mendapatkan filter params dari URL/query parameters
     * bukan dari form data yang dikirim via POST
     */
    /**
     * ✅ Method untuk mendapatkan filter params dari URL/query parameters
     * Digunakan untuk mempertahankan filter state setelah operasi CRUD
     */
    private function getOriginalFilterParams(Request $request)
    {
        $filterParams = [];
        // ✅ Tambahkan 'bulan' dan 'tahun' ke dalam array filters
        $filters = ['progress', 'status', 'unit_or_telda', 'uic', 'search', 'bulan', 'tahun'];

        // ✅ PRIORITAS 1: Ambil dari query parameters (URL) - ini yang paling penting
        foreach ($filters as $filter) {
            $value = $request->query($filter);
            if (!empty($value)) {
                $filterParams[$filter] = $value;
            }
        }

        // ✅ PRIORITAS 2: Ambil dari hidden inputs (dari form POST) untuk setiap filter
        // yang belum ada di query parameters
        foreach ($filters as $filter) {
            // Jika filter ini belum ada di filterParams (dari query), cek di hidden inputs
            if (!isset($filterParams[$filter])) {
                $hiddenValue = $request->input("filter_{$filter}");
                if (!empty($hiddenValue)) {
                    $filterParams[$filter] = $hiddenValue;
                }
            }
        }

        return $filterParams;
    }

    /**
     * Validate request data
     */
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'agenda' => 'required|string',
            'unit_or_telda' => 'nullable|string',
            'start_date' => 'nullable|date',
            'notes_to_follow_up' => 'nullable|string',
            'uic' => 'nullable|string',
            'progress' => 'nullable|string',
            'response_uic' => 'nullable|string',
        ]);
    }

    /**
     * Sync data with warroom table
     */
    private function syncToWarroom(Supportneeded $supportneeded)
    {
        if ($supportneeded->status === 'Action') {
            Newwarroom::updateOrCreate(
                ['supportneeded_id' => $supportneeded->id],
                [
                    'tgl' => $supportneeded->start_date,
                    'agenda' => $supportneeded->agenda,
                    'uic' => $supportneeded->uic,
                    'support_needed' => $supportneeded->notes_to_follow_up,
                    'peserta' => null,
                    'pembahasan' => null,
                    'action_plan' => null,
                    'info_kompetitor' => null,
                    'jumlah_action_plan' => 0,
                    'update_action_plan' => null,
                    'status_action_plan' => $supportneeded->status,
                    'supportneeded_id' => $supportneeded->id,
                ]
            );
        } else {
            Newwarroom::where('supportneeded_id', $supportneeded->id)->delete();
        }
    }

    /**
     * Get detail data for popup (API endpoint)
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

        $query = Supportneeded::where('progress', $progress);

        if ($uic) {
            $query->where('uic', $uic);
        } elseif ($agenda) {
            $query->where('agenda', $agenda);
        } elseif ($unit) {
            $query->where('unit_or_telda', $unit);
        } else {
            return response()->json(['message' => 'Missing query parameter'], 400);
        }

        $data = $query->get();
        return response()->json($data);
    }
}