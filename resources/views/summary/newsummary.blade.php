@extends('layouts.layout')

@section('title', 'Summary Report')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Import existing styles from Support Needed page */
        @import url('{{ asset('css/snstyle.css') }}');

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
            padding: 0.75rem;
            text-align: center;
            white-space: nowrap;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
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
                                        <th>Open</th>
                                        <th>% Open</th>
                                        <th>On Progress</th>
                                        <th>% On Progress</th>
                                        <th>Done</th>
                                        <th>% Done</th>
                                        <th>Need Discuss</th>
                                        <th>% Need Discuss</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($byUic) && count($byUic) > 0)
                                        @foreach ($byUic as $index => $row)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $row['uic'] }}</strong></td>
                                                <td class="status-open">{{ $row['open'] }}</td>
                                                <td class="percentage status-open">{{ $row['open_percent'] }}%</td>
                                                <td class="status-progress">{{ $row['progress'] }}</td>
                                                <td class="percentage status-progress">{{ $row['progress_percent'] }}%</td>
                                                <td class="status-done">{{ $row['done'] }}</td>
                                                <td class="percentage status-done">{{ $row['done_percent'] }}%</td>
                                                <td class="status-discuss">{{ $row['discuss'] }}</td>
                                                <td class="percentage status-discuss">{{ $row['discuss_percent'] }}%</td>
                                                <td><strong>{{ $row['total'] }}</strong></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11" class="text-center">No data available</td>
                                        </tr>
                                    @endif
                                    @if(isset($totalUic))
                                        <tr class="table-secondary total-row">
                                            <td colspan="2"><strong>TOTAL</strong></td>
                                            <td><strong>{{ $totalUic['open'] ?? 0 }}</strong></td>
                                            <td><strong>{{ $totalUic['open_percent'] ?? 0 }}%</strong></td>
                                            <td><strong>{{ $totalUic['progress'] ?? 0 }}</strong></td>
                                            <td><strong>{{ $totalUic['progress_percent'] ?? 0 }}%</strong></td>
                                            <td><strong>{{ $totalUic['done'] ?? 0 }}</strong></td>
                                            <td><strong>{{ $totalUic['done_percent'] ?? 0 }}%</strong></td>
                                            <td><strong>{{ $totalUic['discuss'] ?? 0 }}</strong></td>
                                            <td><strong>{{ $totalUic['discuss_percent'] ?? 0 }}%</strong></td>
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
                                        <th>Agenda</th>
                                        <th>Open</th>
                                        <th>% Open</th>
                                        <th>On Progress</th>
                                        <th>% On Progress</th>
                                        <th>Done</th>
                                        <th>% Done</th>
                                        <th>Need Discuss</th>
                                        <th>% Need Discuss</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($byAgenda) && count($byAgenda) > 0)
                                        @foreach ($byAgenda as $index => $row)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $row['agenda'] }}</strong></td>
                                                <td class="status-open">{{ $row['open'] }}</td>
                                                <td class="percentage status-open">{{ $row['open_percent'] }}%</td>
                                                <td class="status-progress">{{ $row['progress'] }}</td>
                                                <td class="percentage status-progress">{{ $row['progress_percent'] }}%</td>
                                                <td class="status-done">{{ $row['done'] }}</td>
                                                <td class="percentage status-done">{{ $row['done_percent'] }}%</td>
                                                <td class="status-discuss">{{ $row['discuss'] }}</td>
                                                <td class="percentage status-discuss">{{ $row['discuss_percent'] }}%</td>
                                                <td><strong>{{ $row['total'] }}</strong></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11" class="text-center">No data available</td>
                                        </tr>
                                    @endif
                                    @if(isset($totalAgenda))
                                        <tr class="table-secondary total-row">
                                            <td colspan="2"><strong>TOTAL</strong></td>
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
                                        <th>Unit</th>
                                        <th>Open</th>
                                        <th>% Open</th>
                                        <th>On Progress</th>
                                        <th>% On Progress</th>
                                        <th>Done</th>
                                        <th>% Done</th>
                                        <th>Need Discuss</th>
                                        <th>% Need Discuss</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($byUnit) && count($byUnit) > 0)
                                        @foreach ($byUnit as $index => $row)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $row['unit_or_telda'] }}</strong></td>
                                                <td class="status-open">{{ $row['open'] }}</td>
                                                <td class="percentage status-open">{{ $row['open_percent'] }}%</td>
                                                <td class="status-progress">{{ $row['progress'] }}</td>
                                                <td class="percentage status-progress">{{ $row['progress_percent'] }}%</td>
                                                <td class="status-done">{{ $row['done'] }}</td>
                                                <td class="percentage status-done">{{ $row['done_percent'] }}%</td>
                                                <td class="status-discuss">{{ $row['discuss'] }}</td>
                                                <td class="percentage status-discuss">{{ $row['discuss_percent'] }}%</td>
                                                <td><strong>{{ $row['total'] }}</strong></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11" class="text-center">No data available</td>
                                        </tr>
                                    @endif
                                    @if(isset($totalUnit))
                                        <tr class="table-secondary total-row">
                                            <td colspan="2"><strong>TOTAL</strong></td>
                                            <td><strong>{{ $totalUnit['open'] ?? 0 }}</strong></td>
                                            <td><strong>{{ $totalUnit['open_percent'] ?? 0 }}%</strong></td>
                                            <td><strong>{{ $totalUnit['progress'] ?? 0 }}</strong></td>
                                            <td><strong>{{ $totalUnit['progress_percent'] ?? 0 }}%</strong></td>
                                            <td><strong>{{ $totalUnit['done'] ?? 0 }}</strong></td>
                                            <td><strong>{{ $totalUnit['done_percent'] ?? 0 }}%</strong></td>
                                            <td><strong>{{ $totalUnit['discuss'] ?? 0 }}</strong></td>
                                            <td><strong>{{ $totalUnit['discuss_percent'] ?? 0 }}%</strong></td>
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
@endsection