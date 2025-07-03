@extends('layouts.app')

@section('title', 'Summary')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/summary.css') }}">
@endpush

@section('content')
<div class="content-demo">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: 36px; font-weight: bold; color: #4A0E4E; margin: 0;">SUMMARY</h1>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <!-- Category -->
        <div class="collab-container" style="width: 200px; flex-shrink: 0;">
            <div class="collab-header">
                <h2>CATEGORY</h2>
            </div>
            <div class="collab-table-container">
                <table class="collab-table">
                    <thead><tr><th>&nbsp;</th></tr></thead>
                    <tbody>
                        <tr><td class="bg-danger text-white fw-bold">Total</td></tr>
                        <tr><td>Close</td></tr>
                        <tr><td>Progress</td></tr>
                        <tr><td>Need Discuss</td></tr>
                        <tr><td>Eskalasi</td></tr>
                        <tr class="bg-info-subtle"><td>%Close</td></tr>
                        <tr class="bg-warning-subtle"><td>Rata2 %Progress</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Support Needed -->
        <div class="collab-container" style="flex: 1;">
            <div class="collab-header"><h2>SUPPORT NEEDED</h2></div>
            <div class="collab-table-container">
                <table class="collab-table">
                    <thead>
                        <tr>
                            <th>TELDA CORE</th><th>AM CORE</th><th>UNIT CORE</th><th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="bg-danger text-white fw-bold">{{ $data_sntelda_count }}</td>
                            <td class="bg-danger text-white fw-bold">{{ $data_snam_count }}</td>
                            <td class="bg-danger text-white fw-bold">{{ $data_snunit_count }}</td>
                            <td class="bg-danger text-white fw-bold">{{ $total_sn }}</td>
                        </tr>
                        <tr>
                            <td>{{ $data_sntelda_done }}</td>
                            <td>{{ $data_snam_done }}</td>
                            <td>{{ $data_snunit_done }}</td>
                            <td>{{ $close_sn }}</td>
                        </tr>
                        <tr>
                            <td>{{ $data_sntelda_progress }}</td>
                            <td>{{ $data_snam_progress }}</td>
                            <td>{{ $data_snunit_progress }}</td>
                            <td>{{ $progress_sn }}</td>
                        </tr>
                        <tr>
                            <td>{{ $data_sntelda_discuss }}</td>
                            <td>{{ $data_snam_discuss }}</td>
                            <td>{{ $data_snunit_discuss }}</td>
                            <td>{{ $discuss_sn }}</td>
                        </tr>
                        <tr>
                            <td>{{ $data_sntelda_eskalasi }}</td>
                            <td>{{ $data_snam_eskalasi }}</td>
                            <td>{{ $data_snunit_eskalasi }}</td>
                            <td>{{ $eskalasi_sn }}</td>
                        </tr>
                        <tr class="bg-info-subtle">
                            <td>{{ $data_sntelda_percent_close }}%</td>
                            <td>{{ $data_snam_percent_close }}%</td>
                            <td>{{ $data_snunit_percent_close }}%</td>
                            <td>{{ $percent_close_sn }}%</td>
                        </tr>
                        <tr class="bg-warning-subtle">
                            <td>{{ $data_sntelda_avg_progress }}%</td>
                            <td>{{ $data_snam_avg_progress }}%</td>
                            <td>{{ $data_snunit_avg_progress }}%</td>
                            <td>{{ $avg_progress_sn }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Eskalasi -->
        <div class="collab-container" style="flex: 1;">
            <div class="collab-header"><h2>ESKALASI</h2></div>
            <div class="collab-table-container">
                <table class="collab-table">
                    <thead>
                        <tr>
                            <th>to TREG</th><th>to TIF_TA</th><th>to TSEL</th>
                            <th>to GSD</th><th>Others</th><th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="bg-danger text-white fw-bold">{{ $data_treg_count }}</td>
                            <td class="bg-danger text-white fw-bold">{{ $data_tifta_count }}</td>
                            <td class="bg-danger text-white fw-bold">{{ $data_tsel_count }}</td>
                            <td class="bg-danger text-white fw-bold">{{ $data_gsd_count }}</td>
                            <td class="bg-danger text-white fw-bold">0</td>
                            <td class="bg-danger text-white fw-bold">{{ $total_eskalasi }}</td>
                        </tr>
                        <tr>
                            <td>{{ $data_treg_done }}</td>
                            <td>{{ $data_tifta_done }}</td>
                            <td>{{ $data_tsel_done }}</td>
                            <td>{{ $data_gsd_done }}</td>
                            <td>0</td>
                            <td>{{ $close_eskalasi }}</td>
                        </tr>
                        <tr>
                            <td>{{ $data_treg_progress }}</td>
                            <td>{{ $data_tifta_progress }}</td>
                            <td>{{ $data_tsel_progress }}</td>
                            <td>{{ $data_gsd_progress }}</td>
                            <td>0</td>
                            <td>{{ $progress_eskalasi }}</td>
                        </tr>
                        <tr>
                            <td>{{ $data_treg_discuss }}</td>
                            <td>{{ $data_tifta_discuss }}</td>
                            <td>{{ $data_tsel_discuss }}</td>
                            <td>{{ $data_gsd_discuss }}</td>
                            <td>0</td>
                            <td>{{ $discuss_eskalasi }}</td>
                        </tr>
                        <tr>
                            <td>{{ $data_treg_eskalasi }}</td>
                            <td>{{ $data_tifta_eskalasi }}</td>
                            <td>{{ $data_tsel_eskalasi }}</td>
                            <td>{{ $data_gsd_eskalasi }}</td>
                            <td>0</td>
                            <td>{{ $eskalasi_eskalasi }}</td>
                        </tr>
                        <tr class="bg-info-subtle">
                            <td>{{ $data_treg_percent_close }}%</td>
                            <td>{{ $data_tifta_percent_close }}%</td>
                            <td>{{ $data_tsel_percent_close }}%</td>
                            <td>{{ $data_gsd_percent_close }}%</td>
                            <td>0%</td>
                            <td>{{ $percent_close_eskalasi }}%</td>
                        </tr>
                        <tr class="bg-warning-subtle">
                            <td>{{ $data_treg_avg_progress }}%</td>
                            <td>{{ $data_tifta_avg_progress }}%</td>
                            <td>{{ $data_tsel_avg_progress }}%</td>
                            <td>{{ $data_gsd_avg_progress }}%</td>
                            <td>0%</td>
                            <td>{{ $avg_progress_eskalasi }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Total Summary -->
    <div class="collab-container">
        <div class="collab-header">
            <h2>TOTAL</h2>
        </div>
        <div class="collab-table-container">
            <table class="collab-table">
                <thead>
                    <tr>
                        <th>Total Cases</th><th>Close Cases</th><th>In Progress</th>
                        <th>Need Discuss</th><th>Eskalasi</th><th>%Close</th><th>Rata2 %Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="bg-primary text-white fw-bold">{{ $total_all }}</td>
                        <td class="bg-primary text-white fw-bold">{{ $close_all }}</td>
                        <td class="bg-primary text-white fw-bold">{{ $progress_all }}</td>
                        <td class="bg-primary text-white fw-bold">{{ $discuss_all }}</td>
                        <td class="bg-primary text-white fw-bold">{{ $eskalasi_all }}</td>
                        <td class="bg-primary text-white fw-bold">{{ $percent_close_all }}%</td>
                        <td class="bg-primary text-white fw-bold">{{ $avg_progress_all }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
