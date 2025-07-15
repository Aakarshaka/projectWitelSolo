<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\supportneeded;

class SumController extends Controller
{
    public function index()
    {
        $data = supportneeded::all();

        $byUIC = $this->generateSummary($data, 'uic');
        $byAgenda = $this->generateSummary($data, 'agenda');
        $byUnit = $this->generateSummary($data, 'unit_or_telda');

        return view('summary.newsummary', [
            'byUic' => $byUIC['rows'],
            'totalUic' => $byUIC['total'],
            'byAgenda' => $byAgenda['rows'],
            'totalAgenda' => $byAgenda['total'],
            'byUnit' => $byUnit['rows'],
            'totalUnit' => $byUnit['total'],
        ]);
    }

    private function generateSummary($data, $groupBy)
    {
        $summaryRows = [];

        $statuses = [
            'Open' => 'open',
            'On Progress' => 'progress',
            'Need Discuss' => 'discuss',
            'Done' => 'done',
        ];

        $grouped = $data->groupBy($groupBy);

        // Baris per grup
        foreach ($grouped as $key => $items) {
            $row = [
                $groupBy => $key,
                'open' => 0,
                'progress' => 0,
                'done' => 0,
                'discuss' => 0,
                'total' => $items->count(),
            ];

            foreach ($statuses as $progressLabel => $fieldKey) {
                $count = $items->where('progress', $progressLabel)->count();
                $row[$fieldKey] = $count;
                $row["{$fieldKey}_percent"] = $row['total'] > 0 ? round(($count / $row['total']) * 100) : 0;
            }

            $summaryRows[] = $row;
        }

        // Total baris
        $totalRow = [
            'open' => 0,
            'progress' => 0,
            'done' => 0,
            'discuss' => 0,
            'total' => $data->count(),
        ];

        foreach ($statuses as $progressLabel => $fieldKey) {
            $count = $data->where('progress', $progressLabel)->count();
            $totalRow[$fieldKey] = $count;
            $totalRow["{$fieldKey}_percent"] = $totalRow['total'] > 0 ? round(($count / $totalRow['total']) * 100) : 0;
        }

        return [
            'rows' => $summaryRows,
            'total' => $totalRow,
        ];
    }
}
