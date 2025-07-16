<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sntelda;
use App\Models\snunit;
use App\Models\snam;
use App\Models\gsd;
use App\Models\tsel;
use App\Models\treg;
use App\Models\tifta;
use App\Models\witel;

class SummaryController extends Controller
{
    public function index()
    {
        // --- TELDA CORE ---
        $data_sntelda = sntelda::all();
        $data_sntelda_count = $data_sntelda->count();
        $data_sntelda_done = $data_sntelda->where('status', 'Done')->count();
        $data_sntelda_progress = $data_sntelda->where('status', 'Progress')->count();
        $data_sntelda_eskalasi = $data_sntelda->where('status', 'Eskalasi')->count();
        $data_sntelda_discuss = $data_sntelda->where('status', 'Need Discuss')->count();
        $data_sntelda_percent_close = $data_sntelda_count > 0 ? round(($data_sntelda_done / $data_sntelda_count) * 100) : 0;
        $data_sntelda_avg_progress = number_format($data_sntelda->pluck('complete')->avg() ?? 0, 2);

        // --- UNIT CORE ---
        $data_snunit = snunit::all();
        $data_snunit_count = $data_snunit->count();
        $data_snunit_done = $data_snunit->where('status', 'Done')->count();
        $data_snunit_progress = $data_snunit->where('status', 'Progress')->count();
        $data_snunit_eskalasi = $data_snunit->where('status', 'Eskalasi')->count();
        $data_snunit_discuss = $data_snunit->where('status', 'Need Discuss')->count();
        $data_snunit_percent_close = $data_snunit_count > 0 ? round(($data_snunit_done / $data_snunit_count) * 100) : 0;
        $data_snunit_avg_progress = number_format($data_snunit->pluck('complete')->avg() ?? 0, 2);

        // --- AM CORE ---
        $data_snam = snam::all();
        $data_snam_count = $data_snam->count();
        $data_snam_done = $data_snam->where('status', 'Done')->count();
        $data_snam_progress = $data_snam->where('status', 'Progress')->count();
        $data_snam_eskalasi = $data_snam->where('status', 'Eskalasi')->count();
        $data_snam_discuss = $data_snam->where('status', 'Need Discuss')->count();
        $data_snam_percent_close = $data_snam_count > 0 ? round(($data_snam_done / $data_snam_count) * 100) : 0;
        $data_snam_avg_progress = number_format($data_snam->pluck('complete')->avg() ?? 0, 2);

        // --- SUPPORT NEEDED TOTAL ---
        $total_sn = $data_sntelda_count + $data_snunit_count + $data_snam_count;
        $close_sn = $data_sntelda_done + $data_snunit_done + $data_snam_done;
        $progress_sn = $data_sntelda_progress + $data_snunit_progress + $data_snam_progress;
        $eskalasi_sn = $data_sntelda_eskalasi + $data_snunit_eskalasi + $data_snam_eskalasi;
        $discuss_sn = $data_sntelda_discuss + $data_snunit_discuss + $data_snam_discuss;
        $percent_close_sn = $total_sn > 0 ? round(($close_sn / $total_sn) * 100) : 0;
        $avg_progress_sn = number_format(collect([
            $data_sntelda_avg_progress,
            $data_snunit_avg_progress,
            $data_snam_avg_progress
        ])->avg() ?? 0, 2);

        // --- Eskalasi Data ---
        $data_treg = treg::all();
        $data_treg_count = $data_treg->count();
        $data_treg_done = $data_treg->where('status', 'Done')->count();
        $data_treg_progress = $data_treg->where('status', 'Progress')->count();
        $data_treg_eskalasi = $data_treg->where('status', 'Eskalasi')->count();
        $data_treg_discuss = $data_treg->where('status', 'Need Discuss')->count();
        $data_treg_percent_close = $data_treg_count > 0 ? round(($data_treg_done / $data_treg_count) * 100) : 0;
        $data_treg_avg_progress = number_format($data_treg->pluck('complete')->avg() ?? 0, 2);

        $data_tifta = tifta::all();
        $data_tifta_count = $data_tifta->count();
        $data_tifta_done = $data_tifta->where('status', 'Done')->count();
        $data_tifta_progress = $data_tifta->where('status', 'Progress')->count();
        $data_tifta_eskalasi = $data_tifta->where('status', 'Eskalasi')->count();
        $data_tifta_discuss = $data_tifta->where('status', 'Need Discuss')->count();
        $data_tifta_percent_close = $data_tifta_count > 0 ? round(($data_tifta_done / $data_tifta_count) * 100) : 0;
        $data_tifta_avg_progress = number_format($data_tifta->pluck('complete')->avg() ?? 0, 2);

        $data_gsd = gsd::all();
        $data_gsd_count = $data_gsd->count();
        $data_gsd_done = $data_gsd->where('status', 'Done')->count();
        $data_gsd_progress = $data_gsd->where('status', 'Progress')->count();
        $data_gsd_eskalasi = $data_gsd->where('status', 'Eskalasi')->count();
        $data_gsd_discuss = $data_gsd->where('status', 'Need Discuss')->count();
        $data_gsd_percent_close = $data_gsd_count > 0 ? round(($data_gsd_done / $data_gsd_count) * 100) : 0;
        $data_gsd_avg_progress = number_format($data_gsd->pluck('complete')->avg() ?? 0, 2);

        $data_tsel = tsel::all();
        $data_tsel_count = $data_tsel->count();
        $data_tsel_done = $data_tsel->where('status', 'Done')->count();
        $data_tsel_progress = $data_tsel->where('status', 'Progress')->count();
        $data_tsel_eskalasi = $data_tsel->where('status', 'Eskalasi')->count();
        $data_tsel_discuss = $data_tsel->where('status', 'Need Discuss')->count();
        $data_tsel_percent_close = $data_tsel_count > 0 ? round(($data_tsel_done / $data_tsel_count) * 100) : 0;
        $data_tsel_avg_progress = number_format($data_tsel->pluck('complete')->avg() ?? 0, 2);

        $data_witel = witel::all();
        $data_witel_count = $data_witel->count();
        $data_witel_done = $data_witel->where('status', 'Done')->count();
        $data_witel_progress = $data_witel->where('status', 'Progress')->count();
        $data_witel_eskalasi = $data_witel->where('status', 'Eskalasi')->count();
        $data_witel_discuss = $data_witel->where('status', 'Need Discuss')->count();
        $data_witel_percent_close = $data_witel_count > 0 ? round(($data_witel_done / $data_witel_count) * 100) : 0;
        $data_witel_avg_progress = number_format($data_witel->pluck('complete')->avg() ?? 0, 2);

        // --- Eskalasi TOTAL ---
        $total_eskalasi = $data_treg_count + $data_tifta_count + $data_gsd_count + $data_tsel_count + $data_witel_count;
        $close_eskalasi = $data_treg_done + $data_tifta_done + $data_gsd_done + $data_tsel_done + $data_witel_done;
        $progress_eskalasi = $data_treg_progress + $data_tifta_progress + $data_gsd_progress + $data_tsel_progress + $data_witel_progress;
        $eskalasi_eskalasi = $data_treg_eskalasi + $data_tifta_eskalasi + $data_gsd_eskalasi + $data_tsel_eskalasi + $data_witel_eskalasi;
        $discuss_eskalasi = $data_treg_discuss + $data_tifta_discuss + $data_gsd_discuss + $data_tsel_discuss + $data_witel_discuss;
        $percent_close_eskalasi = $total_eskalasi > 0 ? round(($close_eskalasi / $total_eskalasi) * 100) : 0;
        $avg_progress_eskalasi = number_format(collect([
            $data_treg_avg_progress,
            $data_tifta_avg_progress,
            $data_gsd_avg_progress,
            $data_tsel_avg_progress,
            $data_witel_avg_progress
        ])->avg() ?? 0, 2);

        // --- Total Summary ---
        $total_all = $total_sn + $total_eskalasi;
        $close_all = $close_sn + $close_eskalasi;
        $progress_all = $progress_sn + $progress_eskalasi;
        $eskalasi_all = $eskalasi_sn + $eskalasi_eskalasi;
        $discuss_all = $discuss_sn + $discuss_eskalasi;
        $percent_close_all = $total_all > 0 ? round(($close_all / $total_all) * 100) : 0;
        $avg_progress_all = number_format(collect([$avg_progress_sn, $avg_progress_eskalasi])->avg() ?? 0, 2);

        return view('supportNeeded.summary', compact(
            'data_sntelda_count', 'data_sntelda_done', 'data_sntelda_progress', 'data_sntelda_eskalasi', 'data_sntelda_discuss', 'data_sntelda_percent_close', 'data_sntelda_avg_progress',
            'data_snunit_count', 'data_snunit_done', 'data_snunit_progress', 'data_snunit_eskalasi', 'data_snunit_discuss', 'data_snunit_percent_close', 'data_snunit_avg_progress',
            'data_snam_count', 'data_snam_done', 'data_snam_progress', 'data_snam_eskalasi', 'data_snam_discuss', 'data_snam_percent_close', 'data_snam_avg_progress',
            'total_sn', 'close_sn', 'progress_sn', 'eskalasi_sn', 'discuss_sn', 'percent_close_sn', 'avg_progress_sn',
            'data_treg_count', 'data_treg_done', 'data_treg_progress', 'data_treg_eskalasi', 'data_treg_discuss', 'data_treg_percent_close', 'data_treg_avg_progress',
            'data_tifta_count', 'data_tifta_done', 'data_tifta_progress', 'data_tifta_eskalasi', 'data_tifta_discuss', 'data_tifta_percent_close', 'data_tifta_avg_progress',
            'data_gsd_count', 'data_gsd_done', 'data_gsd_progress', 'data_gsd_eskalasi', 'data_gsd_discuss', 'data_gsd_percent_close', 'data_gsd_avg_progress',
            'data_tsel_count', 'data_tsel_done', 'data_tsel_progress', 'data_tsel_eskalasi', 'data_tsel_discuss', 'data_tsel_percent_close', 'data_tsel_avg_progress',
            'data_witel_count', 'data_witel_done', 'data_witel_progress', 'data_witel_eskalasi', 'data_witel_discuss', 'data_witel_percent_close', 'data_witel_avg_progress',
            'total_eskalasi', 'close_eskalasi', 'progress_eskalasi', 'eskalasi_eskalasi', 'discuss_eskalasi', 'percent_close_eskalasi', 'avg_progress_eskalasi',
            'total_all', 'close_all', 'progress_all', 'eskalasi_all', 'discuss_all', 'percent_close_all', 'avg_progress_all'
        ));
    }
}
