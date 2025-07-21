<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Newwarroom;
use App\Models\Supportneeded;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class NewdashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan');

        $baseQuery = Newwarroom::query()->whereYear('tgl', $tahun);
        if ($bulan) {
            $baseQuery->whereMonth('tgl', $bulan);
        }

        // ===== QUICK STATS =====
        // 1. Total Users
        $total_users = User::count();

        // 2. Total Activity Logs (Total Perubahan)
        $total_activity_logs = 0;
        
        // Cek apakah menggunakan spatie/laravel-activitylog
        if (class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            $total_activity_logs = Activity::count();
        } else {
            // Jika ada tabel activity_logs custom
            if (Schema::hasTable('activity_logs')) {
                $total_activity_logs = DB::table('activity_logs')->count();
            }
        }

        // ===== DATA LAINNYA (untuk chart dan analisis) =====
        $total_agenda = $baseQuery->count();
        $total_action_plan = (clone $baseQuery)->sum('jumlah_action_plan');
        $total_eskalasi = (clone $baseQuery)->where('status_action_plan', 'Eskalasi')->count();
        $total_closed = (clone $baseQuery)->where('status_action_plan', 'Closed')->count();

        $bulan_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $jumlah_agenda_per_bulan = [];

        if (!$bulan) {
            for ($i = 1; $i <= 12; $i++) {
                $jumlah_agenda_per_bulan[] = Newwarroom::whereYear('tgl', $tahun)
                    ->whereMonth('tgl', $i)
                    ->count();
            }
        } else {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $bulan_labels = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $bulan_labels[] = $i;
                $jumlah_agenda_per_bulan[] = Newwarroom::whereYear('tgl', $tahun)
                    ->whereMonth('tgl', $bulan)
                    ->whereDay('tgl', $i)
                    ->count();
            }
        }

        $status_labels = ['Open', 'Progress', 'Eskalasi', 'Closed'];
        $status_counts = [];
        foreach ($status_labels as $label) {
            $status_counts[] = (clone $baseQuery)->where('status_action_plan', $label)->count();
        }

        $trend_action_plan = [];
        if (!$bulan) {
            for ($i = 1; $i <= 12; $i++) {
                $trend_action_plan[] = Newwarroom::whereYear('tgl', $tahun)
                    ->whereMonth('tgl', $i)
                    ->sum('jumlah_action_plan');
            }
        } else {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $trend_action_plan[] = Newwarroom::whereYear('tgl', $tahun)
                    ->whereMonth('tgl', $bulan)
                    ->whereDay('tgl', $i)
                    ->sum('jumlah_action_plan');
            }
        }

        $top_issues = [];
        if (Schema::hasColumn('newwarrooms', 'kategori')) {
            $top_issues = (clone $baseQuery)
                ->select('kategori', DB::raw('count(*) as total'))
                ->groupBy('kategori')
                ->orderBy('total', 'desc')
                ->take(5)
                ->get();
        }

        $completion_rate = $total_agenda > 0 ? round(($total_closed / $total_agenda) * 100, 1) : 0;

        $weekly_data = $bulan ? $this->getWeeklyData($tahun, $bulan) : [];

        $priority_labels = ['High', 'Medium', 'Low'];
        $priority_counts = [];
        if (Schema::hasColumn('newwarrooms', 'priority')) {
            foreach ($priority_labels as $priority) {
                $priority_counts[] = (clone $baseQuery)
                    ->where('priority', $priority)
                    ->count();
            }
        }

        // ===== Tambahan dari Supportneeded =====
        $supportItems = Supportneeded::get();
        $total_support = $supportItems->count();
        $closed_support = $supportItems->where('progress', 'Done')->count();
        $close_percentage = $total_support > 0 ? round(($closed_support / $total_support) * 100, 1) : 0;

        $progressMap = [
            'Open' => 0,
            'Need Discuss' => 25,
            'On Progress' => 75,
            'Done' => 100,
        ];

        $totalProgress = 0;
        $count = 0;
        foreach ($supportItems as $item) {
            if (isset($progressMap[$item->progress])) {
                $totalProgress += $progressMap[$item->progress];
                $count++;
            }
        }
        $avg_support_progress = $count > 0 ? round($totalProgress / $count, 1) : 0;

        $support_status_distribution = Supportneeded::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // ===== UPCOMING AGENDA =====
        $upcoming_agenda = $this->getUpcomingAgenda();

        return view('dashboard.newdashboard', compact(
            'tahun', 'bulan', 
            // Quick Stats
            'total_users', 'total_activity_logs',
            // Data lainnya
            'total_agenda', 'total_action_plan', 'total_eskalasi', 'total_closed',
            'bulan_labels', 'jumlah_agenda_per_bulan', 'status_labels', 'status_counts',
            'trend_action_plan', 'top_issues', 'completion_rate', 'weekly_data',
            'priority_labels', 'priority_counts',
            'total_support', 'closed_support', 'close_percentage', 'avg_support_progress', 'support_status_distribution',
            'upcoming_agenda'
        ));
    }

    private function getWeeklyData($tahun, $bulan)
    {
        $weeklyData = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $weekRanges = [
            ['start' => 1, 'end' => 7, 'label' => 'Minggu 1'],
            ['start' => 8, 'end' => 14, 'label' => 'Minggu 2'],
            ['start' => 15, 'end' => 21, 'label' => 'Minggu 3'],
            ['start' => 22, 'end' => $daysInMonth, 'label' => 'Minggu 4']
        ];

        foreach ($weekRanges as $week) {
            $count = Newwarroom::whereYear('tgl', $tahun)
                ->whereMonth('tgl', $bulan)
                ->whereBetween(DB::raw('DAY(tgl)'), [$week['start'], $week['end']])
                ->count();

            $weeklyData[] = [
                'label' => $week['label'],
                'count' => $count
            ];
        }

        return $weeklyData;
    }

    private function getUpcomingAgenda()
    {
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addDays(7);
        $upcoming = collect();

        // Get upcoming warroom agenda
        $warroomColumns = Schema::getColumnListing('newwarrooms');
        $warroomSelect = ['tgl as date'];
        
        // Check for common title/description columns
        if (in_array('agenda', $warroomColumns)) {
            $warroomSelect[] = 'agenda as title';
        } elseif (in_array('title', $warroomColumns)) {
            $warroomSelect[] = 'title';
        } elseif (in_array('name', $warroomColumns)) {
            $warroomSelect[] = 'name as title';
        } else {
            $warroomSelect[] = 'id as title'; // fallback
        }

        if (in_array('status_action_plan', $warroomColumns)) {
            $warroomSelect[] = 'status_action_plan as status';
        }

        if (in_array('priority', $warroomColumns)) {
            $warroomSelect[] = 'priority';
        }

        $warroomAgenda = Newwarroom::select($warroomSelect)
            ->whereBetween('tgl', [$today, $nextWeek])
            ->whereNotIn('status_action_plan', ['Closed', 'Done'])
            ->orderBy('tgl', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'title' => is_numeric($item->title) ? "Warroom Agenda #{$item->title}" : $item->title,
                    'type' => 'warroom',
                    'status' => $item->status ?? 'Open',
                    'priority' => $item->priority ?? 'Medium',
                    'days_left' => Carbon::parse($item->date)->diffInDays(Carbon::today(), false) * -1
                ];
            });

        // Get upcoming support needed agenda
        $supportColumns = Schema::getColumnListing('supportneededs');
        $supportSelect = [];
        
        // Find date column
        $dateColumn = null;
        foreach (['target_date', 'due_date', 'deadline', 'tgl', 'date', 'created_at'] as $col) {
            if (in_array($col, $supportColumns)) {
                $dateColumn = $col;
                break;
            }
        }

        if ($dateColumn) {
            $supportSelect[] = "{$dateColumn} as date";
            
            // Check for title columns
            if (in_array('title', $supportColumns)) {
                $supportSelect[] = 'title';
            } elseif (in_array('subject', $supportColumns)) {
                $supportSelect[] = 'subject as title';
            } elseif (in_array('description', $supportColumns)) {
                $supportSelect[] = 'description as title';
            } else {
                $supportSelect[] = 'id as title';
            }

            if (in_array('progress', $supportColumns)) {
                $supportSelect[] = 'progress as status';
            }

            if (in_array('priority', $supportColumns)) {
                $supportSelect[] = 'priority';
            }

            $supportAgenda = Supportneeded::select($supportSelect)
                ->whereBetween($dateColumn, [$today, $nextWeek])
                ->whereNotIn('progress', ['Done', 'Closed'])
                ->orderBy($dateColumn, 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'title' => is_numeric($item->title) ? "Support Request #{$item->title}" : $item->title,
                        'type' => 'support',
                        'status' => $item->status ?? 'Open',
                        'priority' => $item->priority ?? 'Medium',
                        'days_left' => Carbon::parse($item->date)->diffInDays(Carbon::today(), false) * -1
                    ];
                });
        } else {
            $supportAgenda = collect();
        }

        // Combine and sort
        $upcoming = $warroomAgenda->concat($supportAgenda)
            ->sortBy('date')
            ->take(10);

        return $upcoming;
    }
}