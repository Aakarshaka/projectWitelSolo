<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\supportneeded;

class SumController extends Controller
{
    public function index()
    {
        $data = SupportNeeded::all();

        $byUic = $this->generateSummary($data, 'uic');
        $byAgenda = $this->generateSummary($data, 'agenda');
        $byUnit = $this->generateSummary($data, 'unit_or_telda');

        return view('newsummary', compact('byUic', 'byAgenda', 'byUnit'));
    }

    private function generateSummary($data, $groupBy)
    {
        $summary = [];

        $grouped = $data->groupBy($groupBy);
        $statuses = ['Open', 'On Progress', 'Need Discuss', 'Done'];

        foreach ($grouped as $key => $items) {
            $row = [
                $groupBy => $key,
                'Total' => $items->count()
            ];

            foreach ($statuses as $status) {
                $count = $items->where('progress', $status)->count();
                $row[$status] = $count;
                $row["% $status"] = $row['Total'] > 0 ? round($count / $row['Total'] * 100) : 0;
            }

            $summary[] = $row;
        }

        // Add total row
        $totalRow = [$groupBy => 'TOTAL', 'Total' => $data->count()];
        foreach ($statuses as $status) {
            $count = $data->where('progress', $status)->count();
            $totalRow[$status] = $count;
            $totalRow["% $status"] = $data->count() > 0 ? round($count / $data->count() * 100) : 0;
        }
        $summary[] = $totalRow;

        return $summary;
    }
}