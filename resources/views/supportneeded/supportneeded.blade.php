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
                <button class="add-btn" type="button" onclick="openModal('addSupportModal')">ADD+</button>
            </div>
        </div>

        <div class="controls">
            <div class="filters">
                <form method="GET" action="{{ route('supportneeded.index') }}" class="filters">
                    <div class="filter-group">
                        <label class="filter-label">Type of Agenda</label>
                        <select class="filter-select" name="type_agenda">
                            <option value="">All Agenda</option>
                            <option value="1 ON 1 UIC" {{ request('type_agenda') == '1 ON 1 UIC' ? 'selected' : '' }}>1 ON 1 UIC</option>
                            <option value="1 ON 1 WITEL" {{ request('type_agenda') == '1 ON 1 WITEL' ? 'selected' : '' }}>1 ON 1 WITEL</option>
                            <option value="EVP DIRECTION" {{ request('type_agenda') == 'EVP DIRECTION' ? 'selected' : '' }}>EVP DIRECTION</option>
                            <option value="WBR IT FEB" {{ request('type_agenda') == 'WBR IT FEB' ? 'selected' : '' }}>WBR IT FEB</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Witel or Unit</label>
                        <select class="filter-select" name="unit_or_telda">
                            <option value="">All Witel or Unit</option>
                            <option value="TELDA BLORA" {{ request('unit_or_telda') == 'TELDA BLORA' ? 'selected' : '' }}>TELDA
                                BLORA</option>
                            <option value="TELDA BOYOLALI" {{ request('unit_or_telda') == 'TELDA BOYOLALI' ? 'selected' : '' }}>
                                TELDA BOYOLALI</option>
                            <option value="TELDA JEPARA" {{ request('unit_or_telda') == 'TELDA JEPARA' ? 'selected' : '' }}>TELDA
                                JEPARA</option>
                            <option value="TELDA KLATEN" {{ request('unit_or_telda') == 'TELDA KLATEN' ? 'selected' : '' }}>TELDA
                                KLATEN</option>
                            <option value="TELDA KUDUS" {{ request('unit_or_telda') == 'TELDA KUDUS' ? 'selected' : '' }}>TELDA
                                KUDUS</option>
                            <option value="MEA SOLO" {{ request('unit_or_telda') == 'MEA SOLO' ? 'selected' : '' }}>MEA SOLO
                            </option>
                            <option value="TELDA PATI" {{ request('unit_or_telda') == 'TELDA PATI' ? 'selected' : '' }}>TELDA PATI
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
                            <option value="RSO WITEL" {{ request('unit_or_telda') == 'RSO WITEL' ? 'selected' : '' }}>RSO WITEL
                            </option>
                            <!-- Tambahkan lainnya sesuai kebutuhan -->
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">UIC</label>
                        <select class="filter-select" name="uic">
                            <option value="">All UIC</option>
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
                            <span class="status-badge 
                                {{ $item->status == 'Eskalasi' ? 'status-done' : 
                                ($item->status == 'Action' ? 'status-action' : 
                                ($item->status == 'Support Needed' ? 'status-in-progress' : 'status-empty')) }}">
                                {{ $item->status ?: '-' }}
                            </span>
                        </td>
                        <td class="col-respons">{!! nl2br(e($item->response_uic)) !!}</td>
                        <td class="col-action">
                            <button type="button" class="action-btn edit-btn save-scroll" onclick="populateEditForm({
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
                                <button type="submit" class="action-btn delete-btn save-scroll"
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
    <script>
        const tableWrapper = document.getElementById('tableWrapper');

        // Simpan posisi scroll saat tombol edit/delete diklik
        document.querySelectorAll('.save-scroll').forEach(button => {
            button.addEventListener('click', () => {
                sessionStorage.setItem("tableScrollLeft", tableWrapper.scrollLeft);
                sessionStorage.setItem("tableScrollTop", tableWrapper.scrollTop);
            });
        });

        // Saat halaman dimuat, kembalikan posisi scroll kalau ada
        window.addEventListener("load", function() {
            const savedLeft = sessionStorage.getItem("tableScrollLeft");
            const savedTop = sessionStorage.getItem("tableScrollTop");
            if (savedLeft !== null) tableWrapper.scrollLeft = parseInt(savedLeft);
            if (savedTop !== null) tableWrapper.scrollTop = parseInt(savedTop);
            // Hapus setelah dipakai supaya tidak nyangkut antar navigasi
            sessionStorage.removeItem("tableScrollLeft");
            sessionStorage.removeItem("tableScrollTop");
        });
    </script>
    @endsection