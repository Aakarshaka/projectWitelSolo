@extends('layouts.layout')

@section('title', 'Summary Report')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
@section('content')
<div class="main-content-sum">
    <div class="container-sum">
        {{-- Header Section --}}
        <div class="row">
            <div class="col-12">
                <div class="summary-header">
                    <h1 class="summary-title">SUMMARY REPORT</h1>
                    <p class="summary-subtitle">Follow Up Support Needed - Comprehensive Overview</p>
                </div>
            </div>
        </div>

        <div class="scroll-hint-sum">
            ← Geser ke kiri/kanan untuk melihat semua Colom →
        </div>

        {{-- Table: By UIC --}}
        <div class="row">
            <div class="col-12">
                <div class="table-section">
                    <div class="table-header">
                        <h3>Report Follow Up Support Needed - By UIC</h3>
                    </div>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="summary-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No</th>
                                        <th style="width: 120px;">UIC</th>
                                        <th style="width: 80px;">OPEN</th>
                                        <th style="width: 90px;">% OPEN</th>
                                        <th style="width: 110px;">NEED DISCUSS</th>
                                        <th style="width: 130px;">% NEED DISCUSS</th>
                                        <th style="width: 110px;">ON PROGRESS</th>
                                        <th style="width: 130px;">% ON PROGRESS</th>
                                        <th style="width: 80px;">DONE</th>
                                        <th style="width: 90px;">% DONE</th>
                                        <th style="width: 80px;">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $uicList = ['BS', 'GS', 'RLEGS', 'RSO WITEL','RSO REGIONAL','ED', 'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ', 'RSMES', 'BPPLP', 'SSS'];
                                    @endphp
                                    @foreach ($uicList as $index => $uic)
                                    @php
                                    $rowData = null;
                                    if (isset($byUic) && count($byUic) > 0) {
                                    foreach ($byUic as $data) {
                                    if ($data['uic'] == $uic) {
                                    $rowData = $data;
                                    break;
                                    }
                                    }
                                    }
                                    @endphp
                                    <tr>
                                        <td class="row-number">{{ $index + 1 }}</td>
                                        <td class="entity-name">{{ $uic }}</td>
                                        {{-- OPEN --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-open-summary"
                                                onclick="openDetailModal('uic', '{{ $uic }}', 'Open')">
                                                {{ $rowData['open'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['open_percent'] ?? 0 }}%</span></td>

                                        {{-- NEED DISCUSS --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-discuss-summary"
                                                onclick="openDetailModal('uic', '{{ $uic }}', 'Need Discuss')">
                                                {{ $rowData['discuss'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['discuss_percent'] ?? 0 }}%</span></td>

                                        {{-- PROGRESS --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-progress-summary"
                                                onclick="openDetailModal('uic', '{{ $uic }}', 'On Progress')">
                                                {{ $rowData['progress'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['progress_percent'] ?? 0 }}%</span></td>

                                        {{-- DONE --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-done-summary"
                                                onclick="openDetailModal('uic', '{{ $uic }}', 'Done')">
                                                {{ $rowData['done'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['done_percent'] ?? 0 }}%</span></td>

                                        {{-- TOTAL --}}
                                        <td class="total-count">{{ $rowData['total'] ?? 0 }}</td>
                                    </tr>
                                    @endforeach

                                    @if(isset($totalUic))
                                    <tr class="total-row">
                                        <td colspan="2"><strong>TOTAL</strong></td>
                                        <td><strong>{{ $totalUic['open'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUic['open_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUic['discuss'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUic['discuss_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUic['progress'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUic['progress_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUic['done'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUic['done_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUic['total'] ?? 0 }}</strong></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table: By Agenda --}}
        <div class="row">
            <div class="col-12">
                <div class="table-section">
                    <div class="table-header">
                        <h3>Report Follow Up Support Needed - By Agenda</h3>
                    </div>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="summary-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No</th>
                                        <th style="width: 180px;">AGENDA</th>
                                        <th style="width: 80px;">OPEN</th>
                                        <th style="width: 90px;">% OPEN</th>
                                        <th style="width: 110px;">NEED DISCUSS</th>
                                        <th style="width: 130px;">% NEED DISCUSS</th>
                                        <th style="width: 110px;">ON PROGRESS</th>
                                        <th style="width: 130px;">% ON PROGRESS</th>
                                        <th style="width: 80px;">DONE</th>
                                        <th style="width: 90px;">% DONE</th>
                                        <th style="width: 80px;">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
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
                                    @endphp
                                    @foreach ($agendaList as $index => $agenda)
                                    @php
                                    $rowData = null;
                                    if (isset($byAgenda) && count($byAgenda) > 0) {
                                    foreach ($byAgenda as $data) {
                                    if ($data['agenda'] == $agenda) {
                                    $rowData = $data;
                                    break;
                                    }
                                    }
                                    }
                                    @endphp
                                    <tr>
                                        <td class="row-number">{{ $index + 1 }}</td>
                                        <td class="entity-name">{{ $agenda }}</td>
                                        {{-- OPEN --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-open-summary"
                                                onclick="openDetailModal('agenda', '{{ $agenda }}', 'Open')">
                                                {{ $rowData['open'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['open_percent'] ?? 0 }}%</span></td>

                                        {{-- NEED DISCUSS --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-discuss-summary"
                                                onclick="openDetailModal('agenda', '{{ $agenda }}', 'Need Discuss')">
                                                {{ $rowData['discuss'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['discuss_percent'] ?? 0 }}%</span></td>

                                        {{-- PROGRESS --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-progress-summary"
                                                onclick="openDetailModal('agenda', '{{ $agenda }}', 'On Progress')">
                                                {{ $rowData['progress'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['progress_percent'] ?? 0 }}%</span></td>

                                        {{-- DONE --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-done-summary"
                                                onclick="openDetailModal('agenda', '{{ $agenda }}', 'Done')">
                                                {{ $rowData['done'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['done_percent'] ?? 0 }}%</span></td>

                                        {{-- TOTAL --}}
                                        <td class="total-count">{{ $rowData['total'] ?? 0 }}</td>
                                    </tr>
                                    @endforeach
                                    @if(isset($totalAgenda))
                                    <tr class="total-row">
                                        <td colspan="2"><strong>TOTAL</strong></td>
                                        <td><strong>{{ $totalAgenda['open'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalAgenda['open_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalAgenda['discuss'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalAgenda['discuss_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalAgenda['progress'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalAgenda['progress_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalAgenda['done'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalAgenda['done_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalAgenda['total'] ?? 0 }}</strong></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table: By Unit --}}
        <div class="row">
            <div class="col-12">
                <div class="table-section">
                    <div class="table-header">
                        <h3>Report Follow Up Support Needed - By Unit</h3>
                    </div>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="summary-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No</th>
                                        <th style="width: 140px;">UNIT</th>
                                        <th style="width: 80px;">OPEN</th>
                                        <th style="width: 90px;">% OPEN</th>
                                        <th style="width: 110px;">NEED DISCUSS</th>
                                        <th style="width: 130px;">% NEED DISCUSS</th>
                                        <th style="width: 110px;">ON PROGRESS</th>
                                        <th style="width: 130px;">% ON PROGRESS</th>
                                        <th style="width: 80px;">DONE</th>
                                        <th style="width: 90px;">% DONE</th>
                                        <th style="width: 80px;">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $unitList = ['RSO WITEL','TELDA BLORA', 'TELDA BOYOLALI', 'TELDA JEPARA', 'TELDA KLATEN', 'TELDA KUDUS', 'MEA SOLO', 'TELDA PATI', 'TELDA PURWODADI', 'TELDA REMBANG', 'TELDA SRAGEN', 'TELDA WONOGIRI', 'BS', 'GS', 'PRQ'];
                                    @endphp
                                    @foreach ($unitList as $index => $unit)
                                    @php
                                    $rowData = null;
                                    if (isset($byUnit) && count($byUnit) > 0) {
                                    foreach ($byUnit as $data) {
                                    if ($data['unit_or_telda'] == $unit) {
                                    $rowData = $data;
                                    break;
                                    }
                                    }
                                    }
                                    @endphp
                                    <tr>
                                        <td class="row-number">{{ $index + 1 }}</td>
                                        <td class="entity-name">{{ $unit }}</td>
                                        {{-- OPEN --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-open-summary"
                                                onclick="openDetailModal('unit', '{{ $unit }}', 'Open')">
                                                {{ $rowData['open'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['open_percent'] ?? 0 }}%</span></td>

                                        {{-- NEED DISCUSS --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-discuss-summary"
                                                onclick="openDetailModal('unit', '{{ $unit }}', 'Need Discuss')">
                                                {{ $rowData['discuss'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['discuss_percent'] ?? 0 }}%</span></td>

                                        {{-- PROGRESS --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-progress-summary"
                                                onclick="openDetailModal('unit', '{{ $unit }}', 'On Progress')">
                                                {{ $rowData['progress'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['progress_percent'] ?? 0 }}%</span></td>

                                        {{-- DONE --}}
                                        <td class="col-progress">
                                            <button class="status-badge-summary status-done-summary"
                                                onclick="openDetailModal('unit', '{{ $unit }}', 'Done')">
                                                {{ $rowData['done'] ?? 0 }}
                                            </button>
                                        </td>
                                        <td><span class="percentage-badge">{{ $rowData['done_percent'] ?? 0 }}%</span></td>

                                        {{-- TOTAL --}}
                                        <td class="total-count">{{ $rowData['total'] ?? 0 }}</td>
                                    </tr>
                                    @endforeach
                                    @if(isset($totalUnit))
                                    <tr class="total-row">
                                        <td colspan="2"><strong>TOTAL</strong></td>
                                        <td><strong>{{ $totalUnit['open'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUnit['open_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUnit['discuss'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUnit['discuss_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUnit['progress'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUnit['progress_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUnit['done'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUnit['done_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUnit['total'] ?? 0 }}</strong></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('summary.summodal')
@endsection