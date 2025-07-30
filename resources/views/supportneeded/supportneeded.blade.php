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
                        <div class="stat-value">{{ $close }} ({{ $closePercentage }}%)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Actual Progress</div>
                        <div class="stat-value">{{ round($avgProgress, 1) }}%</div>
                    </div>
                    <a href="{{ url('/supportneeded/export') }}" class="add-btn">EXCEL</a>
                    <button class="add-btn" onclick="openModal('addSupportModal')">ADD+</button>
                </div>
            </div>

            <div class="controls">
                <div class="filters">
                    <form method="GET" action="{{ route('supportneeded.index') }}" class="filters">
                        <div class="filter-group">
                            <label class="filter-label">Bulan</label>
                            <select class="filter-select" name="bulan">
                                <option value="">All Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ sprintf('%02d', $i) }}" {{ request('bulan') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Tahun</label>
                            <select class="filter-select" name="tahun">
                                <option value="">All Tahun</option>
                                @for($year = 2021; $year <= 2030; $year++)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select class="filter-select" name="status">
                                <option value="">All Status</option>
                                <option value="Action" {{ request('status') == 'Action' ? 'selected' : '' }}>Action</option>
                                <option value="Eskalasi" {{ request('status') == 'Eskalasi' ? 'selected' : '' }}>Eskalasi</option>
                                <option value="Support Needed" {{ request('status') == 'Support Needed' ? 'selected' : '' }}>Support Needed</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Progress</label>
                            <select class="filter-select" name="progress">
                                <option value="">All Progress</option>
                                <option value="Open" {{ request('progress') == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Need Discuss" {{ request('progress') == 'Need Discuss' ? 'selected' : '' }}>Need Discuss</option>
                                <option value="On Progress" {{ request('progress') == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                <option value="Done" {{ request('progress') == 'Done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Witel or Unit</label>
                            <select class="filter-select" name="unit_or_telda">
                                <option value="">All Witel or Unit</option>
                                @php
                                    $units = ['TELDA BLORA', 'TELDA BOYOLALI', 'TELDA JEPARA', 'TELDA KLATEN', 'TELDA KUDUS', 'MEA SOLO', 'TELDA PATI', 'TELDA PURWODADI', 'TELDA REMBANG', 'TELDA SRAGEN', 'TELDA WONOGIRI', 'BS', 'GS', 'PRQ', 'SSGS', 'RSO WITEL'];
                                @endphp
                                @foreach($units as $unit)
                                    <option value="{{ $unit }}" {{ request('unit_or_telda') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">UIC</label>
                            <select class="filter-select" name="uic">
                                <option value="">All UIC</option>
                                @php
                                    $uics = ['TELDA BLORA', 'TELDA BOYOLALI', 'TELDA JEPARA', 'TELDA KLATEN', 'TELDA KUDUS', 'MEA SOLO', 'TELDA PATI', 'TELDA PURWODADI', 'TELDA REMBANG', 'TELDA SRAGEN', 'TELDA WONOGIRI', 'BS', 'GS', 'RLEGS', 'RSO REGIONAL', 'RSO WITEL', 'ED', 'TIF', 'TSEL', 'GSD', 'SSGS', 'PRQ', 'RSMES', 'BPPLP', 'SSS', 'LESA V', 'RWS'];
                                @endphp
                                @foreach($uics as $uic)
                                    <option value="{{ $uic }}" {{ request('uic') == $uic ? 'selected' : '' }}>{{ $uic }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="filter-btn">FILTER</button>
                    </form>
                    
                    <form action="{{ route('supportneeded.index') }}" method="GET" class="search-form">
                        <input type="text" name="search" class="search-box" placeholder="Search agenda or unit..." value="{{ request('search') }}">
                        <button type="submit" class="filter-btn">SEARCH</button>
                    </form>
                </div>
            </div>

            <div class="scroll-hint">
                ← Geser ke kiri/kanan untuk melihat semua Colom →
            </div>

            <div class="table-container-sn">
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
                                        {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="col-end">
                                        {{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="col-off">
                                        @if($item->start_date)
                                            @if($item->progress === 'Done' && $item->end_date)
                                                {{ \Carbon\Carbon::parse($item->start_date)->diffInDays(\Carbon\Carbon::parse($item->end_date)) + 1 }}
                                            @else
                                                {{ ceil(\Carbon\Carbon::parse($item->start_date)->diffInHours(\Carbon\Carbon::now()) / 24) }}
                                            @endif
                                            Day
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="col-notes">{!! nl2br(e($item->notes_to_follow_up)) !!}</td>
                                    <td class="col-uic">{{ $item->uic }}</td>
                                    <td class="col-progress">{{ $item->progress }}</td>
                                    @php
                                        $progressData = [
                                            'Open' => ['complete' => 0, 'color' => 'bg-red'],
                                            'Need Discuss' => ['complete' => 25, 'color' => 'bg-orange'],
                                            'On Progress' => ['complete' => 75, 'color' => 'bg-yellow'],
                                            'Done' => ['complete' => 100, 'color' => 'bg-green']
                                        ];
                                        $currentProgress = $progressData[$item->progress] ?? ['complete' => 0, 'color' => 'bg-gray'];
                                    @endphp

                                    <td class="col-complete">
                                        <div class="progress-bar">
                                            <div class="progress-fill {{ $currentProgress['color'] }}" style="width: {{ $currentProgress['complete'] }}%"></div>
                                            <div class="progress-text">{{ $currentProgress['complete'] }}%</div>
                                        </div>
                                    </td>
                                    <td class="col-status">
                                        <span class="status-badge {{ $item->status == 'Eskalasi' ? 'status-done' : ($item->status == 'Action' ? 'status-action' : ($item->status == 'Support Needed' ? 'status-in-progress' : 'status-empty')) }}">
                                            {{ $item->status ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="col-respons">{!! nl2br(e($item->response_uic)) !!}</td>
                                    <td class="col-action">
                                        <div class="btn-group-horizontal">
                                            <button class="action-btn edit-btn save-scroll" onclick="populateEditForm({{ json_encode($item) }}); openModal('editSupportModal');">Edit</button>
                                            <form action="{{ route('supportneeded.destroy', $item->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete-btn save-scroll" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                            </form>
                                            <button class="action-btn copy-btn save-scroll" onclick="copyRowData({{ json_encode($item) }}); openModal('copySupportModal');">Duplicate</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" style="color: #6b7280; font-style: italic; text-align: center;">No data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="sn-footer">Powered by <strong>GIAT CORE</strong></div>
        </div>

        @include('supportneeded.snmodal')
        @include('supportneeded.dupmodal')
        
        @push('scripts')
            <script src="{{ asset('js/tablescript.js') }}"></script>
        @endpush
    </div>
@endsection