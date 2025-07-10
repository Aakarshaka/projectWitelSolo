@extends('layouts.layout')

@section('title', 'Summary Report')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
<style>
    /* Reset and base styles */
    * {
        box-sizing: border-box;
    }

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .main-content {
        margin-left: 60px;
        /* Sesuaikan dengan lebar sidebar */
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .summary-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 1rem;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        text-align: center;
    }

    .summary-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .summary-subtitle {
        font-size: 1.2rem;
        font-weight: 400;
        opacity: 0.95;
        margin: 0;
    }

    /* Table Styling */
    .table-section {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem;
        text-align: center;
    }

    .summary-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .summary-table th,
    .summary-table td {
        padding: 0.75rem 1rem;
        text-align: center;
        white-space: nowrap;
        border-bottom: 1px solid #dee2e6;
    }

    .summary-table th {
    font-weight: 700;
    background-color: #e9ecef;
    color: #212529;
}

    .summary-table .total-row {
        background: #e9ecef;
        font-weight: 700;
        border-top: 2px solid #667eea;
    }

    .percentage {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }

    .status-open {
        color: #dc3545;
    }

    .status-progress {
        color: #ffc107;
    }

    .status-done {
        color: #28a745;
    }

    .status-discuss {
        color: #6f42c1;
    }

    @media (max-width: 768px) {
        .summary-title {
            font-size: 2rem;
        }

        .summary-subtitle {
            font-size: 1rem;
        }

        .summary-table {
            font-size: 0.8rem;
        }

        .summary-table th,
        .summary-table td {
            padding: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <div class="container">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="header">
                    <h1 class="summary-title">SUMMARY REPORT</h1>
                    <p class="summary-subtitle">Follow Up Support Needed - Comprehensive Overview</p>
                </div>
            </div>
        </div>

        {{-- Table: By UIC --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="table-section">
                    <div class="table-header">
                        <h3>Report Follow Up Support Needed - By UIC</h3>
                    </div>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover summary-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>UIC</th>
                                        <th>OPEN</th>
                                        <th>% OPEN</th>
                                        <th>ON PROGRESS</th>
                                        <th>% ON PROGRESS</th>
                                        <th>NEED DISCUSS</th>
                                        <th>% NEED DISCUSS</th>
                                        <th>DONE</th>
                                        <th>% DONE</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $uicList = ['BS','GS','RLEGS','RSO','TIF','TSEL','GSD','SSGS','PRQ','RSMES','BPPLP','SSS'];
                                    @endphp
                                    @foreach ($uicList as $index => $uic)
                                    @php
                                        $rowData = null;
                                        if(isset($byUic) && count($byUic) > 0) {
                                            foreach($byUic as $data) {
                                                if($data['uic'] == $uic) {
                                                    $rowData = $data;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="row-number">{{ $index + 1 }}</td>
                                        <td class="entity-name">{{ $uic }}</td>
                                        <td><span class="status-badge status-open">{{ $rowData['open'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-open">{{ $rowData['open_percent'] ?? 0 }}%</span></td>
                                        <td><span class="status-badge status-progress">{{ $rowData['progress'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-progress">{{ $rowData['progress_percent'] ?? 0 }}%</span></td>
                                        <td><span class="status-badge status-done">{{ $rowData['done'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-done">{{ $rowData['done_percent'] ?? 0 }}%</span></td>
                                        <td><span class="status-badge status-discuss">{{ $rowData['discuss'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-discuss">{{ $rowData['discuss_percent'] ?? 0 }}%</span></td>
                                        <td class="total-count">{{ $rowData['total'] ?? 0 }}</td>
                                    </tr>
                                @endforeach

                                    @if(isset($totalUic))
                                    <tr class="table-secondary total-row">
                                        <td colspan="2"><strong>TOTAL</strong></td>
                                        <td><strong>{{ $totalUic['open'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUic['open_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUic['progress'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUic['progress_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUic['discuss'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUic['discuss_percent'] ?? 0 }}%</strong></td>
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
        <div class="row mb-4">
            <div class="col-12">
                <div class="table-section">
                    <div class="table-header">
                        <h3>Report Follow Up Support Needed - By Agenda</h3>
                    </div>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover summary-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>AGENDA</th>
                                        <th>OPEN</th>
                                        <th>% OPEN</th>
                                        <th>ON PROGRESS</th>
                                        <th>% ON PROGRESS</th>
                                        <th>NEED DISCUSS</th>
                                        <th>% NEED DISCUSS</th>
                                        <th>DONE</th>
                                        <th>% DONE</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $agendaList = [
                                            '1 ON 1 UIC',
                                            '1 ON 1 WITEL',
                                            'EVP DIRECTION',
                                            'WBR IT FEB',
                                            'STRATEGIC MEETING'
                                        ];
                                    @endphp
                                    @foreach ($agendaList as $index => $agenda)
                                    @php
                                        $rowData = null;
                                        if(isset($byAgenda) && count($byAgenda) > 0) {
                                            foreach($byAgenda as $data) {
                                                if($data['agenda'] == $agenda) {
                                                    $rowData = $data;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="row-number">{{ $index + 1 }}</td>
                                        <td class="entity-name">{{ $agenda }}</td>
                                        <td><span class="status-badge status-open">{{ $rowData['open'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-open">{{ $rowData['open_percent'] ?? 0 }}%</span></td>
                                        <td><span class="status-badge status-progress">{{ $rowData['progress'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-progress">{{ $rowData['progress_percent'] ?? 0 }}%</span></td>
                                        <td><span class="status-badge status-done">{{ $rowData['done'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-done">{{ $rowData['done_percent'] ?? 0 }}%</span></td>
                                        <td><span class="status-badge status-discuss">{{ $rowData['discuss'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-discuss">{{ $rowData['discuss_percent'] ?? 0 }}%</span></td>
                                        <td class="total-count">{{ $rowData['total'] ?? 0 }}</td>
                                    </tr>
                                @endforeach
                                    @if(isset($totalAgenda))
                                    <tr class="total-row">
                                        <td colspan="2"><strong><i class="fas fa-calculator"></i> TOTAL</strong></td>
                                        <td><strong>{{ $totalAgenda['open'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalAgenda['open_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalAgenda['progress'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalAgenda['progress_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalAgenda['done'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalAgenda['done_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalAgenda['discuss'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalAgenda['discuss_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalAgenda['total'] ?? 0 }}</strong></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Table: By Unit --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="table-section">
                    <div class="table-header">
                        <h3>Report Follow Up Support Needed - By Unit</h3>
                    </div>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover summary-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>UNIT</th>
                                        <th>OPEN</th>
                                        <th>% OPEN</th>
                                        <th>ON PROGRESS</th>
                                        <th>% ON PROGRESS</th>
                                        <th>NEED DISCUSS</th>
                                        <th>% NEED DISCUSS</th>
                                        <th>DONE</th>
                                        <th>% DONE</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $unitList = ['BLORA', 'BOYOLALI', 'JEPARA', 'KLATEN', 'KUDUS', 'MEA SOLO', 'PATI', 'PURWODADI', 'REMBANG', 'SRAGEN', 'WONOGIRI','BS','GS','PRQ'];
                                    @endphp
                                    @foreach ($unitList as $index => $unit)
                                    @php
                                        $rowData = null;
                                        if(isset($byUnit) && count($byUnit) > 0) {
                                            foreach($byUnit as $data) {
                                                if($data['unit_or_telda'] == $unit) {
                                                    $rowData = $data;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="row-number">{{ $index + 2 }}</td>
                                        <td class="entity-name">{{ $unit }}</td>
                                        <td><span class="status-badge status-open">{{ $rowData['open'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-open">{{ $rowData['open_percent'] ?? 0 }}%</span></td>
                                        <td><span class="status-badge status-progress">{{ $rowData['progress'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-progress">{{ $rowData['progress_percent'] ?? 0 }}%</span></td>
                                        <td><span class="status-badge status-done">{{ $rowData['done'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-done">{{ $rowData['done_percent'] ?? 0 }}%</span></td>
                                        <td><span class="status-badge status-discuss">{{ $rowData['discuss'] ?? 0 }}</span></td>
                                        <td><span class="percentage-badge percentage-discuss">{{ $rowData['discuss_percent'] ?? 0 }}%</span></td>
                                        <td class="total-count">{{ $rowData['total'] ?? 0 }}</td>
                                    </tr>
                                @endforeach
                                    @if(isset($totalUnit))
                                    <tr class="table-secondary total-row">
                                        <td colspan="2"><strong>TOTAL</strong></td>
                                        <td><strong>{{ $totalUnit['open'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUnit['open_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUnit['progress'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUnit['progress_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalUnit['done'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalUnit['done_percent'] ?? 0 }}%</strong></td>
                                        <td><strong>{{ $totalAgenda['discuss'] ?? 0 }}</strong></td>
                                        <td><strong>{{ $totalAgenda['discuss_percent'] ?? 0 }}%</strong></td>
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
@endsection