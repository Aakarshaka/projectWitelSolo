<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivitylogController extends Controller
{
    /**
     * Field yang tidak perlu ditampilkan di modal
     */
    private $excludedFields = [
        'id',
        'created_at',
        'updated_at',
        'uic_approvals'
    ];

    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // FILTER: Action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // FILTER: Bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        // FILTER: Model Type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // FILTER: Search all major fields (description, model_type, changes)
        if ($request->filled('description')) {
            $searchTerm = '%' . $request->description . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', $searchTerm)
                    ->orWhere('model_type', 'like', $searchTerm)
                    ->orWhere('changes', 'like', $searchTerm);
            });
        }

        // Ambil logs dengan pagination jika diperlukan
        $logs = $query->latest()->get();

        // Decode JSON changes untuk setiap log dan filter field yang tidak diinginkan
        $logs->transform(function ($log) {
            if ($log->changes && is_string($log->changes)) {
                $log->changes = json_decode($log->changes, true);
            }
            
            // Filter field yang tidak diinginkan dari changes
            if ($log->changes && is_array($log->changes)) {
                $log->changes = $this->filterExcludedFields($log->changes);
            }
            
            return $log;
        });

        // Ambil semua nama model unik
        $models = ActivityLog::select('model_type')->distinct()->pluck('model_type')->filter();

        // Ambil semua bulan yang tersedia dari data log
        $bulanList = ActivityLog::selectRaw('MONTH(created_at) as bulan')
            ->distinct()
            ->pluck('bulan')
            ->map(function ($b) {
                return str_pad($b, 2, '0', STR_PAD_LEFT);
            })
            ->unique()
            ->sort();

        // Hitung jumlah per aksi dengan filter yang sedang aktif
        $baseQuery = ActivityLog::query();
        
        if ($request->filled('bulan')) {
            $baseQuery->whereMonth('created_at', $request->bulan);
        }
        
        if ($request->filled('model_type')) {
            $baseQuery->where('model_type', $request->model_type);
        }
        
        if ($request->filled('description')) {
            $searchTerm = '%' . $request->description . '%';
            $baseQuery->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', $searchTerm)
                    ->orWhere('model_type', 'like', $searchTerm)
                    ->orWhere('changes', 'like', $searchTerm);
            });
        }

        $countCreate = (clone $baseQuery)->where('action', 'create')->count();
        $countUpdate = (clone $baseQuery)->where('action', 'update')->count();
        $countDelete = (clone $baseQuery)->where('action', 'delete')->count();
        $total = (clone $baseQuery)->count();

        return view('log.activitylog', compact(
            'logs',
            'models',
            'bulanList',
            'countCreate',
            'countUpdate',
            'countDelete',
            'total'
        ));
    }

    /**
     * Filter field yang tidak diinginkan dari array changes
     */
    private function filterExcludedFields($changes)
    {
        if (!is_array($changes)) {
            return $changes;
        }

        $filtered = [];
        foreach ($changes as $field => $change) {
            // Skip field yang ada di daftar excluded
            if (in_array($field, $this->excludedFields)) {
                continue;
            }
            
            $filtered[$field] = $change;
        }

        return $filtered;
    }

    /**
     * Helper method untuk memformat nilai field untuk tampilan
     */
    private function formatFieldValue($value)
    {
        if (is_null($value)) {
            return null;
        }
        
        if (is_bool($value)) {
            return $value ? 'True' : 'False';
        }
        
        if (is_array($value)) {
            return json_encode($value, JSON_PRETTY_PRINT);
        }
        
        if ($value === '') {
            return 'Kosong';
        }
        
        return $value;
    }

    /**
     * Method untuk mendapatkan detail perubahan (jika diperlukan via AJAX)
     */
    public function getLogDetails(Request $request, $logId)
    {
        $log = ActivityLog::with('user')->findOrFail($logId);
        
        $changes = [];
        if ($log->changes && is_string($log->changes)) {
            $changes = json_decode($log->changes, true);
        } elseif ($log->changes && is_array($log->changes)) {
            $changes = $log->changes;
        }

        // Filter field yang tidak diinginkan
        $changes = $this->filterExcludedFields($changes);

        $formattedChanges = [];
        
        foreach ($changes as $field => $change) {
            $formattedChanges[$field] = [
                'old' => $this->formatFieldValue($change['old'] ?? null),
                'new' => $this->formatFieldValue($change['new'] ?? null)
            ];
        }

        return response()->json([
            'log' => $log,
            'changes' => $formattedChanges,
            'action' => $log->action,
            'model_type' => class_basename($log->model_type),
            'created_at' => $log->created_at->format('d M Y H:i')
        ]);
    }

    /**
     * Method untuk menambah field yang dikecualikan (opsional)
     */
    public function addExcludedField($field)
    {
        if (!in_array($field, $this->excludedFields)) {
            $this->excludedFields[] = $field;
        }
    }

    /**
     * Method untuk menghapus field dari daftar yang dikecualikan (opsional)
     */
    public function removeExcludedField($field)
    {
        $this->excludedFields = array_diff($this->excludedFields, [$field]);
    }

    /**
     * Method untuk mendapatkan daftar field yang dikecualikan (opsional)
     */
    public function getExcludedFields()
    {
        return $this->excludedFields;
    }
}