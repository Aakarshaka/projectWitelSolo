<?php

namespace App\Http\Controllers;

use App\Models\Supportneeded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SumController extends Controller
{
    public function index()
    {
        // Get summary data by UIC
        $byUic = $this->getSummaryByUic();
        $totalUic = $this->getTotalSummary($byUic);

        // Get summary data by Agenda
        $byAgenda = $this->getSummaryByAgenda();
        $totalAgenda = $this->getTotalSummary($byAgenda);

        // Get summary data by Unit
        $byUnit = $this->getSummaryByUnit();
        $totalUnit = $this->getTotalSummary($byUnit);

        return view('summary.newsummary', compact(
            'byUic',
            'totalUic',
            'byAgenda',
            'totalAgenda',
            'byUnit',
            'totalUnit'
        ));
    }

    public function getDetail(Request $request)
    {
        try {
            $type = $request->get('type'); // uic, agenda, unit
            $value = $request->get('value');
            $progress = $request->get('progress');

            Log::info('Detail request received', [
                'type' => $type,
                'value' => $value,
                'progress' => $progress
            ]);

            $query = Supportneeded::query();

            // Filter berdasarkan type
            if ($type === 'uic') {
                // Improved UIC filtering for multiple UICs
                $query->where(function ($q) use ($value) {
                    // Split UIC string by comma and search for each
                    $q->where('uic', 'like', '%' . $value . '%')
                        ->orWhere('uic', '=', $value)
                        ->orWhereRaw("FIND_IN_SET(?, REPLACE(uic, ' ', ''))", [$value])
                        ->orWhereRaw("FIND_IN_SET(?, uic)", [$value]);
                });
            } elseif ($type === 'agenda') {
                $query->where('agenda', $value);
            } elseif ($type === 'unit') {
                $query->where('unit_or_telda', $value);
            }

            // Filter berdasarkan progress
            if ($progress && $progress !== 'all') {
                $query->where('progress', $progress);
            }

            $data = $query->orderBy('start_date', 'desc')->get();

            // Format tanggal dan perhitungan persentase untuk setiap item
            $data = $data->map(function ($item) {
                // Format Start Date dengan timezone Jakarta dan locale Indonesia
                $item->start_date_formatted = $item->start_date 
                    ? Carbon::parse($item->start_date)
                        ->setTimezone('Asia/Jakarta')
                        ->locale('id')
                        ->isoFormat('D MMMM YYYY') 
                    : '';

                // Format End Date dengan timezone Jakarta dan locale Indonesia
                $item->end_date_formatted = $item->end_date 
                    ? Carbon::parse($item->end_date)
                        ->setTimezone('Asia/Jakarta')
                        ->locale('id')
                        ->isoFormat('D MMMM YYYY') 
                    : '';

                // Hitung persentase completion berdasarkan progress
                $item->completion_percentage = $this->calculateCompletionPercentage($item->progress);

                // Tambahkan field formatted untuk display
                $item->start_date_display = $item->start_date_formatted;
                $item->end_date_display = $item->end_date_formatted;
                
                // Ganti field asli dengan yang sudah diformat untuk konsistensi
                $item->start_date = $item->start_date_formatted;
                $item->end_date = $item->end_date_formatted;

                return $item;
            });

            Log::info('Detail query result', [
                'count' => $data->count(),
                'sql' => $query->toSql()
            ]);

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Error in getDetail: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate completion percentage based on progress status
     */
    private function calculateCompletionPercentage($progress)
    {
        switch ($progress) {
            case 'Open':
                return 0;
            case 'Need Discuss':
                return 25;
            case 'On Progress':
                return 75;
            case 'Done':
                return 100;
            default:
                return 0;
        }
    }

    /**
     * Helper method untuk format tanggal yang konsisten
     */
    private function formatDate($date)
    {
        return $date 
            ? Carbon::parse($date)
                ->setTimezone('Asia/Jakarta')
                ->locale('id')
                ->isoFormat('D MMMM YYYY') 
            : '';
    }

    private function getSummaryByUic()
    {
        $uicList = ['BS', 'GS', 'RLEGS', 'RSO WITEL', 'RSO REGIONAL', 'ED', 'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ', 'RSMES', 'BPPLP', 'SSS'];
        $summary = [];

        foreach ($uicList as $uic) {
            // Improved query for multiple UIC support - konsisten dengan SumController
            $baseQuery = function ($query, $progress = null) use ($uic) {
                $query->where(function ($q) use ($uic) {
                    $q->where('uic', 'like', '%' . $uic . '%')
                        ->orWhere('uic', '=', $uic)
                        ->orWhereRaw("FIND_IN_SET(?, REPLACE(uic, ' ', ''))", [$uic])
                        ->orWhereRaw("FIND_IN_SET(?, uic)", [$uic]);
                });

                if ($progress) {
                    $query->where('progress', $progress);
                }

                return $query;
            };

            $open = $baseQuery(Supportneeded::query(), 'Open')->count();
            $discuss = $baseQuery(Supportneeded::query(), 'Need Discuss')->count();
            $progress = $baseQuery(Supportneeded::query(), 'On Progress')->count();
            $done = $baseQuery(Supportneeded::query(), 'Done')->count();

            $total = $open + $discuss + $progress + $done;

            $summary[] = [
                'uic' => $uic,
                'open' => $open,
                'open_percent' => $total > 0 ? round(($open / $total) * 100, 1) : 0,
                'discuss' => $discuss,
                'discuss_percent' => $total > 0 ? round(($discuss / $total) * 100, 1) : 0,
                'progress' => $progress,
                'progress_percent' => $total > 0 ? round(($progress / $total) * 100, 1) : 0,
                'done' => $done,
                'done_percent' => $total > 0 ? round(($done / $total) * 100, 1) : 0,
                'total' => $total
            ];
        }

        return $summary;
    }

    private function getSummaryByAgenda()
    {
        $agendaList = [
            '1 ON 1 AM',
            '1 ON 1 TELDA',
            'WAR',
            'FORUM TIF',
            'FORUM TSEL',
            'FORUM GSD',
            'REVIEW KPI',
            'OTHERS'
        ];

        $summary = [];

        foreach ($agendaList as $agenda) {
            $open = Supportneeded::where('agenda', $agenda)->where('progress', 'Open')->count();
            $discuss = Supportneeded::where('agenda', $agenda)->where('progress', 'Need Discuss')->count();
            $progress = Supportneeded::where('agenda', $agenda)->where('progress', 'On Progress')->count();
            $done = Supportneeded::where('agenda', $agenda)->where('progress', 'Done')->count();
            $total = $open + $discuss + $progress + $done;

            $summary[] = [
                'agenda' => $agenda,
                'open' => $open,
                'open_percent' => $total > 0 ? round(($open / $total) * 100, 1) : 0,
                'discuss' => $discuss,
                'discuss_percent' => $total > 0 ? round(($discuss / $total) * 100, 1) : 0,
                'progress' => $progress,
                'progress_percent' => $total > 0 ? round(($progress / $total) * 100, 1) : 0,
                'done' => $done,
                'done_percent' => $total > 0 ? round(($done / $total) * 100, 1) : 0,
                'total' => $total
            ];
        }

        return $summary;
    }

    private function getSummaryByUnit()
    {
        $unitList = ['RSO WITEL', 'TELDA BLORA', 'TELDA BOYOLALI', 'TELDA JEPARA', 'TELDA KLATEN', 'TELDA KUDUS', 'MEA SOLO', 'TELDA PATI', 'TELDA PURWODADI', 'TELDA REMBANG', 'TELDA SRAGEN', 'TELDA WONOGIRI', 'BS', 'GS', 'PRQ'];
        $summary = [];

        foreach ($unitList as $unit) {
            $open = Supportneeded::where('unit_or_telda', $unit)->where('progress', 'Open')->count();
            $discuss = Supportneeded::where('unit_or_telda', $unit)->where('progress', 'Need Discuss')->count();
            $progress = Supportneeded::where('unit_or_telda', $unit)->where('progress', 'On Progress')->count();
            $done = Supportneeded::where('unit_or_telda', $unit)->where('progress', 'Done')->count();
            $total = $open + $discuss + $progress + $done;

            $summary[] = [
                'unit_or_telda' => $unit,
                'open' => $open,
                'open_percent' => $total > 0 ? round(($open / $total) * 100, 1) : 0,
                'discuss' => $discuss,
                'discuss_percent' => $total > 0 ? round(($discuss / $total) * 100, 1) : 0,
                'progress' => $progress,
                'progress_percent' => $total > 0 ? round(($progress / $total) * 100, 1) : 0,
                'done' => $done,
                'done_percent' => $total > 0 ? round(($done / $total) * 100, 1) : 0,
                'total' => $total
            ];
        }

        return $summary;
    }

    private function getTotalSummary($data)
    {
        $totals = [
            'open' => 0,
            'discuss' => 0,
            'progress' => 0,
            'done' => 0,
            'total' => 0
        ];

        foreach ($data as $item) {
            $totals['open'] += $item['open'];
            $totals['discuss'] += $item['discuss'];
            $totals['progress'] += $item['progress'];
            $totals['done'] += $item['done'];
            $totals['total'] += $item['total'];
        }

        // Calculate percentages
        $totals['open_percent'] = $totals['total'] > 0 ? round(($totals['open'] / $totals['total']) * 100, 1) : 0;
        $totals['discuss_percent'] = $totals['total'] > 0 ? round(($totals['discuss'] / $totals['total']) * 100, 1) : 0;
        $totals['progress_percent'] = $totals['total'] > 0 ? round(($totals['progress'] / $totals['total']) * 100, 1) : 0;
        $totals['done_percent'] = $totals['total'] > 0 ? round(($totals['done'] / $totals['total']) * 100, 1) : 0;

        return $totals;
    }
}