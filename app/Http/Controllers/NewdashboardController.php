<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Newwarroom;
use App\Models\Supportneeded;

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

        return view('dashboard.newdashboard', compact(
            'tahun', 'bulan', 'total_agenda', 'total_action_plan', 'total_eskalasi', 'total_closed',
            'bulan_labels', 'jumlah_agenda_per_bulan', 'status_labels', 'status_counts',
            'trend_action_plan', 'top_issues', 'completion_rate', 'weekly_data',
            'priority_labels', 'priority_counts',
            'total_support', 'closed_support', 'close_percentage', 'avg_support_progress', 'support_status_distribution'
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
}
