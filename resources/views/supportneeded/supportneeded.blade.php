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
                    <a href="{{ url('/supportneeded/export') }}" class="add-btn" type="button">
                        EXCEL
                    </a>
                    <button class="add-btn" type="button" onclick="openModal('addSupportModal')">ADD+</button>
                </div>
            </div>

            <div class="controls">
                <div class="filters">
                    <form method="GET" action="{{ route('supportneeded.index') }}" class="filters">
                        <div class="filter-group">
                            <label class="filter-label">Bulan</label>
                            <select class="filter-select" name="bulan">
                                <option value="">All Bulan</option>
                                <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>January</option>
                                <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>February</option>
                                <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>March</option>
                                <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>May</option>
                                <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>June</option>
                                <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>July</option>
                                <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>August</option>
                                <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>October</option>
                                <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>December</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Tahun</label>
                            <select class="filter-select" name="tahun">
                                <option value="">All Tahun</option>
                                <option value="2021" {{ request('tahun') == '2021' ? 'selected' : '' }}>2021</option>
                                <option value="2022" {{ request('tahun') == '2022' ? 'selected' : '' }}>2022</option>
                                <option value="2023" {{ request('tahun') == '2023' ? 'selected' : '' }}>2023</option>
                                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>2025</option>
                                <option value="2026" {{ request('tahun') == '2026' ? 'selected' : '' }}>2026</option>
                                <option value="2027" {{ request('tahun') == '2027' ? 'selected' : '' }}>2027</option>
                                <option value="2028" {{ request('tahun') == '2028' ? 'selected' : '' }}>2028</option>
                                <option value="2029" {{ request('tahun') == '2029' ? 'selected' : '' }}>2029</option>
                                <option value="2030" {{ request('tahun') == '2030' ? 'selected' : '' }}>2030</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select class="filter-select" name="status">
                                <option value="">All Status</option>
                                <option value="Action" {{ request('status') == 'Action' ? 'selected' : '' }}>Action</option>
                                <option value="Eskalasi" {{ request('status') == 'Eskalasi' ? 'selected' : '' }}>Eskalasi
                                </option>
                                <option value="Support Needed" {{ request('status') == 'Support Needed' ? 'selected' : '' }}>
                                    Support Needed</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Progress</label>
                            <select class="filter-select" name="progress">
                                <option value="">All Progress</option>
                                <option value="Open" {{ request('progress') == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Need Discuss" {{ request('progress') == 'Need Discuss' ? 'selected' : '' }}>
                                    Need Discuss</option>
                                <option value="On Progress" {{ request('progress') == 'On Progress' ? 'selected' : '' }}>On
                                    Progress</option>
                                <option value="Done" {{ request('progress') == 'Done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Witel or Unit</label>
                            <select class="filter-select" name="unit_or_telda">
                                <option value="">All Witel or Unit</option>
                                <option value="TELDA BLORA" {{ request('unit_or_telda') == 'TELDA BLORA' ? 'selected' : '' }}>
                                    TELDA
                                    BLORA</option>
                                <option value="TELDA BOYOLALI" {{ request('unit_or_telda') == 'TELDA BOYOLALI' ? 'selected' : '' }}>
                                    TELDA BOYOLALI</option>
                                <option value="TELDA JEPARA" {{ request('unit_or_telda') == 'TELDA JEPARA' ? 'selected' : '' }}>TELDA
                                    JEPARA</option>
                                <option value="TELDA KLATEN" {{ request('unit_or_telda') == 'TELDA KLATEN' ? 'selected' : '' }}>TELDA
                                    KLATEN</option>
                                <option value="TELDA KUDUS" {{ request('unit_or_telda') == 'TELDA KUDUS' ? 'selected' : '' }}>
                                    TELDA
                                    KUDUS</option>
                                <option value="MEA SOLO" {{ request('unit_or_telda') == 'MEA SOLO' ? 'selected' : '' }}>MEA
                                    SOLO
                                </option>
                                <option value="TELDA PATI" {{ request('unit_or_telda') == 'TELDA PATI' ? 'selected' : '' }}>
                                    TELDA PATI
                                </option>
                                <option value="TELDA PURWODADI" {{ request('unit_or_telda') == 'TELDA PURWODADI' ? 'selected' : '' }}>
                                    TELDA PURWODADI</option>
                                <option value="TELDA REMBANG" {{ request('unit_or_telda') == 'TELDA REMBANG' ? 'selected' : '' }}>TELDA
                                    REMBANG</option>
                                <option value="TELDA SRAGEN" {{ request('unit_or_telda') == 'TELDA SRAGEN' ? 'selected' : '' }}>TELDA
                                    SRAGEN</option>
                                <option value="TELDA WONOGIRI" {{ request('unit_or_telda') == 'TELDA WONOGIRI' ? 'selected' : '' }}>
                                    TELDA WONOGIRI</option>
                                <option value="BS" {{ request('unit_or_telda') == 'BS' ? 'selected' : '' }}>BS</option>
                                <option value="GS" {{ request('unit_or_telda') == 'GS' ? 'selected' : '' }}>GS</option>
                                <option value="PRQ" {{ request('unit_or_telda') == 'PRQ' ? 'selected' : '' }}>PRQ</option>
                                <option value="SSGS" {{ request('unit_or_telda') == 'SSGS' ? 'selected' : '' }}>SSGS</option>
                                <option value="RSO WITEL" {{ request('unit_or_telda') == 'RSO WITEL' ? 'selected' : '' }}>RSO
                                    WITEL
                                </option>
                                <!-- Tambahkan lainnya sesuai kebutuhan -->
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">UIC</label>
                            <select class="filter-select" name="uic">
                                <option value="">All UIC</option>
                                <option value="TELDA BLORA" {{ request('uic') == 'TELDA BLORA' ? 'selected' : '' }}>TELDA
                                    BLORA</option>
                                <option value="TELDA BOYOLALI" {{ request('uic') == 'TELDA BOYOLALI' ? 'selected' : '' }}>
                                    TELDA BOYOLALI</option>
                                <option value="TELDA JEPARA" {{ request('uic') == 'TELDA JEPARA' ? 'selected' : '' }}>TELDA
                                    JEPARA</option>
                                <option value="TELDA KLATEN" {{ request('uic') == 'TELDA KLATEN' ? 'selected' : '' }}>TELDA
                                    KLATEN</option>
                                <option value="TELDA KUDUS" {{ request('uic') == 'TELDA KUDUS' ? 'selected' : '' }}>TELDA
                                    KUDUS</option>
                                <option value="MEA SOLO" {{ request('uic') == 'MEA SOLO' ? 'selected' : '' }}>MEA SOLO
                                </option>
                                <option value="TELDA PATI" {{ request('uic') == 'TELDA PATI' ? 'selected' : '' }}>TELDA PATI
                                </option>
                                <option value="TELDA PURWODADI" {{ request('uic') == 'TELDA PURWODADI' ? 'selected' : '' }}>
                                    TELDA PURWODADI</option>
                                <option value="TELDA REMBANG" {{ request('uic') == 'TELDA REMBANG' ? 'selected' : '' }}>TELDA
                                    REMBANG</option>
                                <option value="TELDA SRAGEN" {{ request('uic') == 'TELDA SRAGEN' ? 'selected' : '' }}>TELDA
                                    SRAGEN</option>
                                <option value="TELDA WONOGIRI" {{ request('uic') == 'TELDA WONOGIRI' ? 'selected' : '' }}>
                                    TELDA WONOGIRI</option>
                                <option value="BS" {{ request('uic') == 'BS' ? 'selected' : '' }}>BS</option>
                                <option value="GS" {{ request('uic') == 'GS' ? 'selected' : '' }}>GS</option>
                                <option value="RLEGS" {{ request('uic') == 'RLEGS' ? 'selected' : '' }}>RLEGS</option>
                                <option value="RSO REGIONAL" {{ request('uic') == 'RSO REGIONAL' ? 'selected' : '' }}>RSO
                                    REGIONAL</option>
                                <option value="RSO WITEL" {{ request('uic') == 'RSO WITEL' ? 'selected' : '' }}>RSO WITEL
                                </option>
                                <option value="ED" {{ request('uic') == 'ED' ? 'selected' : '' }}>ED</option>
                                <option value="TIF" {{ request('uic') == 'TIF' ? 'selected' : '' }}>TIF</option>
                                <option value="TSEL" {{ request('uic') == 'TSEL' ? 'selected' : '' }}>TSEL</option>
                                <option value="GSD" {{ request('uic') == 'GSD' ? 'selected' : '' }}>GSD</option>
                                <option value="SSGS" {{ request('uic') == 'SSGS' ? 'selected' : '' }}>SSGS</option>
                                <option value="PRQ" {{ request('uic') == 'PRQ' ? 'selected' : '' }}>PRQ</option>
                                <option value="RSMES" {{ request('uic') == 'RSMES' ? 'selected' : '' }}>RSMES</option>
                                <option value="BPPLP" {{ request('uic') == 'BPPLP' ? 'selected' : '' }}>BPPLP</option>
                                <option value="SSS" {{ request('uic') == 'SSS' ? 'selected' : '' }}>SSS</option>
                                <option value="LESA V" {{ request('uic') == 'LESA V' ? 'selected' : '' }}>LESA V</option>
                                <!-- Tambahkan lainnya sesuai kebutuhan -->
                            </select>
                        </div>
                        <button type="submit" class="filter-btn">FILTER</button>
                    </form>
                    <form action="{{ route('supportneeded.index') }}" method="GET" class="search-form">
                        <input type="text" name="search" class="search-box" placeholder="Search agenda or unit..."
                            value="{{ request('search') }}">
                        <button type="submit" class="filter-btn">SEARCH</button>
                    </form>
                </div>
            </div>

            <div class="scroll-hint">
                ← Geser ke kiri/kanan untuk melihat semua Colom →
            </div>

            <div class="table-container-sn">
                <div class="table-wrapper" id="tableWrapper">
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
                                                        switch ($item->progress) {
                                                            case 'Open':
                                                                $complete = 0;
                                                                $progressColor = 'bg-red';
                                                                break;
                                                            case 'Need Discuss':
                                                                $complete = 25;
                                                                $progressColor = 'bg-orange';
                                                                break;
                                                            case 'On Progress':
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
                                                            <div class="progress-fill {{ $progressColor }}" style="width: {{ $complete }}%">
                                                            </div>
                                                            <div class="progress-text">{{ $complete }}%</div>
                                                        </div>
                                                    </td>
                                                    <td class="col-status">
                                                        <span class="status-badge 
                                                                                                        {{ $item->status == 'Eskalasi' ? 'status-done' :
                                ($item->status == 'Action' ? 'status-action' :
                                    ($item->status == 'Support Needed' ? 'status-in-progress' : 'status-empty')) }}">
                                                            {{ $item->status ?: '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="col-respons">{!! nl2br(e($item->response_uic)) !!}</td>
                                                    <td class="col-action">
                                                        <div class="btn-group-horizontal">
                                                            <button type="button" class="action-btn edit-btn save-scroll"
                                                                onclick="populateEditForm({
                                                                                                                                                    id: '{{ $item->id }}',
                                                                                                                                                    agenda: '{{ $item->agenda }}',
                                                                                                                                                    unit_or_telda: '{{ $item->unit_or_telda }}',
                                                                                                                                                    start_date: '{{ $item->start_date }}',
                                                                                                                                                    uic: '{{ $item->uic }}',
                                                                                                                                                    progress: '{{ $item->progress }}',
                                                                                                                                                    notes_to_follow_up: `{{ $item->notes_to_follow_up }}`,
                                                                                                                                                    response_uic: `{{ $item->response_uic }}`
                                                                                                                                                    }); openModal('editSupportModal');">Edit</button>
                                                            <form action="{{ route('supportneeded.destroy', $item->id) }}" method="POST"
                                                                style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="action-btn delete-btn save-scroll"
                                                                    onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                                            </form>
                                                            <!-- Tombol Copy -->
                                                            <button type="button" class="action-btn copy-btn save-scroll" onclick="copyRowData({
                                                                                            id: '{{ $item->id }}',
                                                                                            agenda: '{{ $item->agenda }}',
                                                                                            unit_or_telda: '{{ $item->unit_or_telda }}',
                                                                                            start_date: '{{ $item->start_date }}',
                                                                                            end_date: '{{ $item->end_date }}',
                                                                                            uic: '{{ $item->uic }}',
                                                                                            progress: '{{ $item->progress }}',
                                                                                            status: '{{ $item->status }}',
                                                                                            notes_to_follow_up: `{{ $item->notes_to_follow_up }}`,
                                                                                            response_uic: `{{ $item->response_uic }}`
                                                                                        }); openModal('copySupportModal');">Duplicate</button>
                                                        </div>
                                                    </td>
                                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" style="color: #6b7280; font-style: italic; text-align: center; ">No data
                                        available.</td>
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
@endsection