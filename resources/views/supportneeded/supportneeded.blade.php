@extends('layouts.layout')

@section('title', 'SUPPORT NEEDED')

@section('content')

    <div class="main-content">
        <div class="container">
            <div class="header">
                <h1>SUPPORT NEEDED</h1>
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-label">Total</div>
                        <div class="stat-value">{{ $total }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Close</div>
                        <div class="stat-value">{{ $close }} ({{  $closePercentage }}%)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Actual Progress</div>
                        <div class="stat-value">{{ round($avgProgress, 1) }}%</div>
                    </div>
                    <button class="add-btn" type="button" onclick="openModal('addSupportModal')">ADD+</button>
                </div>
            </div>

            <div class="controls">
                <div class="filters">
                    <div class="filter-group">
                        <label class="filter-label">Type of Agenda</label>
                        <select class="filter-select">
                            <option>All Agenda</option>
                            <option>1 ON 1 UIC</option>
                            <option>1 ON 1 WITEL</option>
                            <option>EVP DIRECTION</option>
                            <option>WBR IT FEB</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Witel or Unit</label>
                        <select class="filter-select">
                            <option>All Witel or Unit</option>
                            <option>RLEGS</option>
                            <option>Witel Bali</option>
                            <option>Witel Yogyakarta</option>
                            <option>Witel Suramadu</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">UIC</label>
                        <select class="filter-select">
                            <option>All UIC</option>
                            <option>BPPLP</option>
                            <option>RSO1</option>
                            <option>RSMES</option>
                            <option>RLEGS</option>
                        </select>
                    </div>
                    <button class="filter-btn">FILTER</button>
                </div>
                <div class="search-container">
                    <form action="{{ route('supportneeded.index') }}" method="GET" class="search-form">
                        <input type="text" name="search" class="search-box" placeholder="Search agenda or unit..."
                            value="{{ request('search') }}">
                        <button type="submit" class="filter-btn">Search</button>
                    </form>
                </div>

            </div>

            <div class="scroll-hint">
                ← Geser ke kiri/kanan untuk melihat semua Colom →
            </div>

            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-agenda">Agenda</th>
                            <th class="col-unit">Unit/Telda</th>
                            <th class="col-start">Start Date</th>
                            <th class="col-end">End Date</th>
                            <th class="col-off"># Off Day</th>
                            <th class="col-notes">Notes to Follow Up</th>
                            <th class="col-uic">UIC</th>
                            <th class="col-progress">Progress</th>
                            <th class="col-complete">% Complete</th>
                            <th class="col-status">Status</th>
                            <th class="col-respons">Response UIC</th>
                            <th class="col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td class="col-no">{{ $index + 1 }}</td>
                                <td class="col-agenda">{{ $item->agenda }}</td>
                                <td class="col-unit">{{ $item->unit_or_telda }}</td>
                                <td class="col-start">
                                    {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : '-'  }}
                                </td>
                                <td class="col-end">
                                    {{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('d M Y') : '-'  }}
                                </td>
                                <td class="col-off">
                                    @if($item->start_date && $item->end_date)
                                        {{ \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1 }}
                                        Day
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="col-notes">{!! nl2br(e($item->notes_to_follow_up)) !!}</td>
                                <td class="col-uic">{{ $item->uic }}</td>
                                <td class="col-progress">{{ $item->progress }}</td>
                                @php
                                    switch ($item->progress) {
                                        case 'Open':
                                            $complete = 0;
                                            $progressColor = 'bg-red';
                                            break;
                                        case 'Need Discuss':
                                            $complete = 25;
                                            $progressColor = 'bg-orange';
                                            break;
                                        case 'Progress':
                                            $complete = 75;
                                            $progressColor = 'bg-yellow';
                                            break;
                                        case 'Done':
                                            $complete = 100;
                                            $progressColor = 'bg-green';
                                            break;
                                        default:
                                            $complete = 0;
                                            $progressColor = 'bg-gray';
                                    }
                                @endphp

                                <td class="col-complete">
                                    <div class="progress-bar">
                                        <div class="progress-fill {{ $progressColor }}" style="width: {{ $complete }}%"></div>
                                        <div class="progress-text">{{ $complete }}%</div>
                                    </div>
                                </td>
                                <td class="col-status">
                                    @php
                                        $escalationUics = ['RSMES', 'RLEGS', 'BPPLP', 'RSO', 'SSS', 'TIF', 'TSEL', 'GSD'];

                                        if (empty($item->unit_or_telda) && empty($item->uic)) {
                                            $statusText = '';
                                            $statusClass = '';
                                        } elseif (in_array($item->uic, $escalationUics)) {
                                            $statusText = 'Eskalasi';
                                            $statusClass = 'status-done'; // contoh class-nya
                                        } elseif ($item->unit_or_telda == $item->uic) {
                                            $statusText = 'Action';
                                            $statusClass = 'status-action';
                                        } else {
                                            $statusText = 'Support Needed';
                                            $statusClass = 'status-in-progress';
                                        }
                                    @endphp

                                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td class="col-respons">{!! nl2br(e($item->response_uic)) !!}</td>
                                <td class="col-action">
                                    <button type="button" class="action-btn edit-btn" onclick="populateEditForm({
                                                    id: '{{ $item->id }}',
                                                    agenda: '{{ $item->agenda }}',
                                                    unit_or_telda: '{{ $item->unit_or_telda }}',
                                                    start_date: '{{ $item->start_date }}',
                                                    end_date: '{{ $item->end_date }}',
                                                    uic: '{{ $item->uic }}',
                                                    progress: '{{ $item->progress }}',
                                                    notes_to_follow_up: `{{ $item->notes_to_follow_up }}`,
                                                    response_uic: `{{ $item->response_uic }}`
                                                    }); openModal('editSupportModal');">Edit</button>
                                    <form action="{{ route('supportneeded.destroy', $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" style="text-align:center;">No data available.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
            <div class="sn-footer">Powered by <strong>GIAT CORE</strong></div>
        </div>

        @include('supportneeded.snmodal')
@endsection