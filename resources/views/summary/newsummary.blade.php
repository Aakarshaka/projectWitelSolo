@extends('layouts.layout')

@section('title', 'Summary Report')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .main-content-sum {
            margin-left: 60px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .container-sum {
            padding: 20px;
            width: 100%;
        }

        /* Header Styling */
        .summary-header {
            background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
            color: white;
            padding: 15px 30px;
            margin: -20px -20px 20px -20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(139, 21, 56, 0.3);
            flex-wrap: wrap;
        }

        .summary-title {
            font-size: 28px;
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
            text-align: left;
        }

        .summary-subtitle {
            font-size: 20px;
            font-weight: 400;
            opacity: 0.95;
            padding: 10px 10px;
        }

        /* Table Section Styling */
        .table-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 3rem;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .table-header {
            background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
            color: white;
            padding: 1.5rem 2rem;
            text-align: center;
            border-bottom: 3px solid #8b1538;
        }

        .table-header h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .table-container {
            padding: 0;
        }

        .table-responsive {
            margin: 0;
            border-radius: 0;
        }

        /* Enhanced Table Styling */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            margin: 0;
            background: white;
        }

        .summary-table th,
        .summary-table td {
            padding: 1rem 0.75rem;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .summary-table th {
            font-weight: 600;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
            border-bottom: 2px solid #8b1538;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .summary-table tbody tr {
            transition: all 0.2s ease;
        }

        .summary-table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .summary-table tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        /* Entity name column */
        .entity-name {
            text-align: left !important;
            font-weight: 500;
            color: #495057;
            white-space: nowrap;
            padding-left: 1.5rem !important;
            min-width: 150px;
        }

        .row-number {
            font-weight: 600;
            color: #6c757d;
            background-color: #f8f9fa;
            width: 60px;
        }

        /* Status badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-block;
            min-width: 40px;
        }

        .status-open {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .status-progress {
            background-color: #fef3c7;
            color: #d97706;
            border: 1px solid #fed7aa;
        }

        .status-done {
            background-color: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .status-discuss {
            background-color: #f3e8ff;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }

        /* Percentage badges */
        .percentage-badge {
            font-size: 0.8rem;
            font-weight: 500;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }

        /* Total row styling */
        .total-row {
            background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%) !important;
            border-top: 3px solid #8b1538 !important;
            font-weight: 700 !important;
        }

        .total-row td {
            padding: 1.2rem 0.75rem !important;
            color: #495057 !important;
            border-bottom: 2px solid #8b1538 !important;
        }

        .total-count {
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
            border-left: 3px solid #8b1538;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .container-sum {
                padding: 0 1rem;
            }

            .summary-title {
                font-size: 2.2rem;
            }

            .summary-subtitle {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 768px) {
            .main-content-sum {
                margin-left: 0;
                padding: 1rem 0;
            }

            .summary-header {
                padding: 2rem 1rem;
                margin-bottom: 2rem;
            }

            .summary-title {
                font-size: 1.8rem;
            }

            .summary-subtitle {
                font-size: 1rem;
            }

            .table-section {
                margin-bottom: 2rem;
            }

            .table-header {
                padding: 1rem;
            }

            .table-header h3 {
                font-size: 1.1rem;
            }

            .summary-table {
                font-size: 0.8rem;
            }

            .summary-table th,
            .summary-table td {
                padding: 0.6rem 0.4rem;
            }

            .entity-name {
                padding-left: 0.8rem !important;
            }
        }

        @media (max-width: 576px) {
            .container-sum {
                padding: 0 0.5rem;
            }

            .summary-table th,
            .summary-table td {
                padding: 0.4rem 0.2rem;
                font-size: 0.75rem;
            }
        }
    </style>
@endpush

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
                                            $uicList = ['BS', 'GS', 'RLEGS', 'RSO', 'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ', 'RSMES', 'BPPLP', 'SSS'];
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
                                            <button class="status-badge status-open"
                                                onclick="openDetailModal('uic', '{{ $uic }}', 'Open')">
                                                {{ $rowData['open'] ?? 0 }}
                                            </button>
                                            </td>
                                            <td><span class="percentage-badge">{{ $rowData['open_percent'] ?? 0 }}%</span></td>

                                            {{-- NEED DISCUSS --}}
                                            <td class="col-progress">
                                            <button class="status-badge status-discuss"
                                                onclick="openDetailModal('uic', '{{ $uic }}', 'Need Discuss')">
                                                {{ $rowData['discuss'] ?? 0 }}
                                            </button>
                                            </td>
                                            <td><span class="percentage-badge">{{ $rowData['discuss_percent'] ?? 0 }}%</span></td>

                                            {{-- PROGRESS --}}
                                            <td class="col-progress">
                                            <button class="status-badge status-progress"
                                                onclick="openDetailModal('uic', '{{ $uic }}', 'Progress')">
                                                {{ $rowData['progress'] ?? 0 }}
                                            </button>
                                            </td>
                                            <td><span class="percentage-badge">{{ $rowData['progress_percent'] ?? 0 }}%</span></td>

                                            {{-- DONE --}}
                                            <td class="col-progress">
                                            <button class="status-badge status-done"
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
                                            <button class="status-badge status-open"
                                                onclick="openDetailModal('agenda', '{{ $agenda }}', 'Open')">
                                                {{ $rowData['open'] ?? 0 }}
                                            </button>
                                            </td>
                                            <td><span class="percentage-badge">{{ $rowData['open_percent'] ?? 0 }}%</span></td>

                                            {{-- NEED DISCUSS --}}
                                            <td class="col-progress">
                                            <button class="status-badge status-discuss"
                                                onclick="openDetailModal('agenda', '{{ $agenda }}', 'Need Discuss')">
                                                {{ $rowData['discuss'] ?? 0 }}
                                            </button>
                                            </td>
                                            <td><span class="percentage-badge">{{ $rowData['discuss_percent'] ?? 0 }}%</span></td>

                                            {{-- PROGRESS --}}
                                            <td class="col-progress">
                                            <button class="status-badge status-progress"
                                                onclick="openDetailModal('agenda', '{{ $agenda }}', 'Progress')">
                                                {{ $rowData['progress'] ?? 0 }}
                                            </button>
                                            </td>
                                            <td><span class="percentage-badge">{{ $rowData['progress_percent'] ?? 0 }}%</span></td>

                                            {{-- DONE --}}
                                            <td class="col-progress">
                                            <button class="status-badge status-done"
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
                                            $unitList = ['TELDA BLORA', 'TELDA BOYOLALI', 'TELDA JEPARA', 'TELDA KLATEN', 'TELDA KUDUS', 'MEA SOLO', 'TELDA PATI', 'TELDA PURWODADI', 'TELDA REMBANG', 'TELDA SRAGEN', 'TELDA WONOGIRI', 'BS', 'GS', 'PRQ'];
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
                                            <button class="status-badge status-open"
                                                onclick="openDetailModal('unit', '{{ $unit }}', 'Open')">
                                                {{ $rowData['open'] ?? 0 }}
                                            </button>
                                            </td>
                                            <td><span class="percentage-badge">{{ $rowData['open_percent'] ?? 0 }}%</span></td>

                                            {{-- NEED DISCUSS --}}
                                            <td class="col-progress">
                                            <button class="status-badge status-discuss"
                                                onclick="openDetailModal('unit', '{{ $unit }}', 'Need Discuss')">
                                                {{ $rowData['discuss'] ?? 0 }}
                                            </button>
                                            </td>
                                            <td><span class="percentage-badge">{{ $rowData['discuss_percent'] ?? 0 }}%</span></td>

                                            {{-- PROGRESS --}}
                                            <td class="col-progress">
                                            <button class="status-badge status-progress"
                                                onclick="openDetailModal('unit', '{{ $unit }}', 'Progress')">
                                                {{ $rowData['progress'] ?? 0 }}
                                            </button>
                                            </td>
                                            <td><span class="percentage-badge">{{ $rowData['progress_percent'] ?? 0 }}%</span></td>

                                            {{-- DONE --}}
                                            <td class="col-progress">
                                            <button class="status-badge status-done"
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
    <!-- Detail Modal -->
    <div id="detailModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeDetailModal()">&times;</span>
            <h2>Detail Agenda</h2>
            <div id="detailContent">
                <p>Loading...</p>
            </div>
        </div>
    </div>

    <style>
        .modal {
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(3px);
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { 
                transform: translateY(-30px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            width: 95%;
            max-width: 1200px;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease-out;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e0e0e0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }

        .close-btn:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            padding: 30px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 14px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow-x: auto;
        }

        .table-container1 {
            overflow-x: auto;
            margin: 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .detail-table th {
            background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            border: none;
            white-space: nowrap;
            min-width: 100px;
        }

        .detail-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
            word-wrap: break-word;
        }

        .detail-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .detail-table tr:hover {
            background-color: #e3f2fd;
            transition: background-color 0.2s ease;
        }

        .detail-table td:nth-child(1) {
            text-align: center;
            font-weight: 600;
            background-color: #f5f5f5;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-action {
            background-color: #ff9800;
            color: white;
        }

        

        .status-pending {
            background-color: #f44336;
            color: white;
        }

        .progress-bar {
            background-color: #e0e0e0;
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
            margin-top: 2px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4caf50, #8bc34a);
            transition: width 0.3s ease;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 16px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 16px;
        }

        .loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .modal-content {
                margin: 2% auto;
                width: 98%;
                max-height: 95vh;
            }
            
            .modal-header {
                padding: 15px 20px;
            }
            
            .modal-header h2 {
                font-size: 20px;
            }
            
            .modal-body {
                padding: 20px;
                max-height: 60vh;
            }
            
            .detail-table {
                font-size: 12px;
            }
            
            .detail-table th,
            .detail-table td {
                padding: 8px 6px;
            }
        }
    </style>

    <script>
    function openDetailModal(type, value, progress) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        content.innerHTML = '<p>Loading...</p>';
        modal.style.display = 'block';

        let url = '/supportneeded/detail?';
        if (type === 'uic') {
            url += `uic=${encodeURIComponent(value)}&progress=${encodeURIComponent(progress)}`;
        } else if (type === 'agenda') {
            url += `agenda=${encodeURIComponent(value)}&progress=${encodeURIComponent(progress)}`;
        } else if (type === 'unit') {
            url += `unit=${encodeURIComponent(value)}&progress=${encodeURIComponent(progress)}`;
        } else {
            content.innerHTML = '<p>Invalid request type.</p>';
            return;
        }

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error("Failed to fetch");
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    content.innerHTML = '<p>No data found.</p>';
                } else {
                    let html = '<div class="table-container1">'; 
                    html += '<table class="detail-table">';
                    html += `<thead><tr>
                    <th>No</th>
                    <th>Agenda</th>
                    <th>Unit/Telda</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th># Off Day</th>
                    <th>Notes to Follow Up</th>
                    <th>UIC</th>
                    <th>Progress</th>
                    <th>% Complete</th>
                    <th>Status</th>
                    <th>Response UIC</th>
                </tr></thead><tbody>`;

                    data.forEach((item, index) => {
                        const statusClass = item.status?.toLowerCase() === 'action' ? 'status-action' : 
                                      item.status?.toLowerCase() === 'done' ? 'status-done' : 'status-pending';
                        html += `<tr>
                        <td>${index + 1}</td>
                        <td>${item.agenda || '-'}</td>
                        <td>${item.unit_or_telda || '-'}</td>
                        <td>${item.start_date || '-'}</td>
                        <td>${item.end_date || '-'}</td>
                        <td>${item.off_day || 0}</td>
                        <td>${item.notes_to_follow_up || '-'}</td>
                        <td>${item.uic || '-'}</td>
                        <td>${item.progress || '-'}</td>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <span style="margin-right: 8px;">${item.complete || 0}%</span>
                                <div class="progress-bar" style="width: 60px;">
                                    <div class="progress-fill" style="width: ${item.complete || 0}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="status-badge ${statusClass}">${item.status || '-'}</span></td>
                        <td>${item.response_uic || '-'}</td>
                    </tr>`;
                });

                    html += '</tbody></table>';
                content.innerHTML = html;
                }
            })
            .catch((error) => {
                console.error(error);
                content.innerHTML = '<p>Failed to load data.</p>';
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
    window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target === modal) {
                closeDetailModal();
            }
    }
</script>

@endsection