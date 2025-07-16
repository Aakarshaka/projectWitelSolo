<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivitylogController extends Controller
{
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

        // FILTER: Search all major fields (description, model_type, data, changes)
        if ($request->filled('description')) {
            $searchTerm = '%' . $request->description . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', $searchTerm)
                    ->orWhere('model_type', 'like', $searchTerm)
                    ->orWhere('changes', 'like', $searchTerm);
                // Hapus: ->orWhere('data', 'like', $searchTerm)
            });
        }

        $logs = $query->latest()->get();

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

        // Hitung jumlah per aksi
        $countCreate = ActivityLog::where('action', 'create')->count();
        $countUpdate = ActivityLog::where('action', 'update')->count();
        $countDelete = ActivityLog::where('action', 'delete')->count();
        $total = ActivityLog::count();

        return view('auth.activitylog', compact(
            'logs',
            'models',
            'bulanList',
            'countCreate',
            'countUpdate',
            'countDelete',
            'total'
        ));
    }
}
