@extends('layouts.layout')

@section('title', 'War Room')

@section('content')
<div class="main-content-wr">
    <div class="container-wr">
        <div class="header-wr">
            <h1>WARROOM ACTIVITY</h1>
            <div class="stats-container-wr">
                <div class="stat-card-wr">
                    <div class="stat-label-wr">Masuk Eskalasi</div>
                    <div class="stat-value-wr">{{ $jumlah_eskalasi }}</div>
                </div>
                <div class="stat-card-wr">
                    <div class="stat-label-wr">Jumlah Action Plan</div>
                    <div class="stat-value-wr">{{ $jumlah_action_plan }}</div>
                </div>
                <div class="stat-card-wr">
                    <div class="stat-label-wr">Jumlah Agenda</div>
                    <div class="stat-value-wr">{{ $jumlah_agenda }}</div>
                </div>
                <a href="{{ url('/newwarroom/export') }}" style="underline:none" class="add-btn" type="button">
                    EXCEL
                </a>

                <button class="add-btn" type="button" onclick="openModal('addModal')">ADD+</button>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
            <button type="button" class="close-btn" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
        @endif

        <!-- New Layout Section -->
        <div class="content-layout-wr">
            <!-- Left Side - Forum Summary -->
            <div class="forum-summary-wr">
                <div class="forum-container-wr">
                    <div class="forum-header-wr">
                        <h3 class="forum-title-wr">FORUM WAR ROOM ACTIVITY</h3>
                        <div class="forum-period-wr">
                            <i class="fas fa-calendar-alt"></i>
                            @php
                            $nama_bulan = [
                            '01' => 'January',
                            '02' => 'February',
                            '03' => 'March',
                            '04' => 'April',
                            '05' => 'May',
                            '06' => 'June',
                            '07' => 'July',
                            '08' => 'August',
                            '09' => 'September',
                            '10' => 'October',
                            '11' => 'November',
                            '12' => 'December',
                            ];
                            @endphp

                            <span>
                                {{ !empty($bulan) && isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : 'Semua Bulan' }}
                                {{ !empty($tahun) ? $tahun : 'Semua Tahun' }}
                            </span>
                        </div>
                    </div>

                    <div class="forum-content-wr">
                        <div class="forum-item-wr">
                            <div class="agenda-list-wr">
                                @if(count($nama_agenda) > 0)
                                @foreach($nama_agenda as $index => $agenda)
                                <div class="agenda-item-wr">
                                    <span class="agenda-number-wr">{{ $index + 1 }}</span>
                                    <span class="agenda-text-wr">{{ $agenda }}</span>
                                </div>
                                @endforeach
                                @else
                                <div class="no-agenda-wr">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Belum ada agenda untuk periode ini</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Filter and Search -->
            <div class="filter-search-wr">
                <!-- Filter Section -->
                <div class="filter-section-wr">
                    <div class="filter-container-wr">
                        <h3 class="filter-title-wr">Filter Data</h3>
                        <form method="GET" action="{{ route('newwarroom.index') }}" class="filters">
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
                                <label class="filter-label">UIC</label>
                                <select class="filter-select" name="uic">
                                    <option value="">All UIC</option>
                                    <option value="TELDA BLORA" {{ request('uic') == 'TELDA BLORA' ? 'selected' : '' }}>TELDA BLORA</option>
                                    <option value="TELDA BOYOLALI" {{ request('uic') == 'TELDA BOYOLALI' ? 'selected' : '' }}>TELDA BOYOLALI</option>
                                    <option value="TELDA JEPARA" {{ request('uic') == 'TELDA JEPARA' ? 'selected' : '' }}>TELDA JEPARA</option>
                                    <option value="TELDA KLATEN" {{ request('uic') == 'TELDA KLATEN' ? 'selected' : '' }}>TELDA KLATEN</option>
                                    <option value="TELDA KUDUS" {{ request('uic') == 'TELDA KUDUS' ? 'selected' : '' }}>TELDA KUDUS</option>
                                    <option value="TELDA MEA SOLO" {{ request('uic') == 'TELDA MEA SOLO' ? 'selected' : '' }}>TELDA MEA SOLO</option>
                                    <option value="TELDA PATI" {{ request('uic') == 'TELDA PATI' ? 'selected' : '' }}>TELDA PATI</option>
                                    <option value="TELDA PURWODADI" {{ request('uic') == 'TELDA PURWODADI' ? 'selected' : '' }}>TELDA PURWODADI</option>
                                    <option value="TELDA REMBANG" {{ request('uic') == 'TELDA REMBANG' ? 'selected' : '' }}>TELDA REMBANG</option>
                                    <option value="TELDA SRAGEN" {{ request('uic') == 'TELDA SRAGEN' ? 'selected' : '' }}>TELDA SRAGEN</option>
                                    <option value="TELDA WONOGIRI" {{ request('uic') == 'TELDA WONOGIRI' ? 'selected' : '' }}>TELDA WONOGIRI</option>
                                    <option value="BS" {{ request('uic') == 'BS' ? 'selected' : '' }}>BS</option>
                                    <option value="GS" {{ request('uic') == 'GS' ? 'selected' : '' }}>GS</option>
                                    <option value="RLEGS" {{ request('uic') == 'RLEGS' ? 'selected' : '' }}>RLEGS</option>
                                    <option value="RSO REGIONAL" {{ request('uic') == 'RSO REGIONAL' ? 'selected' : '' }}>RSO REGIONAL</option>
                                    <option value="RSO WITEL" {{ request('uic') == 'RSO WITEL' ? 'selected' : '' }}>RSO WITEL</option>
                                    <option value="ED" {{ request('uic') == 'ED' ? 'selected' : '' }}>ED</option>
                                    <option value="TIF" {{ request('uic') == 'TIF' ? 'selected' : '' }}>TIF</option>
                                    <option value="TSEL" {{ request('uic') == 'TSEL' ? 'selected' : '' }}>TSEL</option>
                                    <option value="GSD" {{ request('uic') == 'GSD' ? 'selected' : '' }}>GSD</option>
                                    <option value="SSGS" {{ request('uic') == 'SSGS' ? 'selected' : '' }}>SSGS</option>
                                    <option value="PRQ" {{ request('uic') == 'PRQ' ? 'selected' : '' }}>PRQ</option>
                                    <option value="RSMES" {{ request('uic') == 'RSMES' ? 'selected' : '' }}>RSMES</option>
                                    <option value="BPPLP" {{ request('uic') == 'BPPLP' ? 'selected' : '' }}>BPPLP</option>
                                    <option value="SSS" {{ request('uic') == 'SSS' ? 'selected' : '' }}>SSS</option>
                                </select>
                            </div>

                            {{-- ✅ Tombol Filter --}}
                            <div class="filter-group-wr">
                                <button type="submit" class="filter-btn-wr">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="search-section-wr">
                    <div class="search-container-wr">
                        <h3 class="search-title-wr">Search Data</h3>
                        <form method="GET" class="search-form-wr">
                            <div class="search-inputs-wr">
                                <div class="search-group-wr">
                                    <div class="search-input-container-wr">
                                        <input type="text" name="search" class="search-input-wr"
                                            placeholder="Cari agenda, UIC, peserta, atau pembahasan..."
                                            value="{{ request('search') }}">
                                        <button type="submit" class="search-btn-wr">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="scroll-hint-wr">
            ← Geser ke kiri/kanan untuk melihat semua Kolom →
        </div>

        <div class="table-container-wr">
            <div class="table-wrapper-wr">
                <table class="table-wr">
                    <thead>
                        <tr>
                            <th class="col-no-wr">No</th>
                            <th class="col-tgl-wr">Tgl</th>
                            <th class="col-agenda-wr">Agenda</th>
                            <th class="col-uic-wr">UIC</th>
                            <th class="col-peserta-wr">Peserta</th>
                            <th class="col-pembahasan-wr">Pembahasan</th>
                            <th class="col-ac-wr">Action Plan</th>
                            <th class="col-sn-wr">Support Needed</th>
                            <th class="col-kompetitor-wr">Info Kompetitor</th>
                            <th class="col-jap-wr">Jumlah Action Plan</th>
                            <th class="col-uap-wr">Update Action Plan</th>
                            <th class="col-sap-wr">Status Action Plan</th>
                            <th class="col-action-wr">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warroomData as $index => $item)
                        <tr>
                            <td class="col-no-wr">{{ $index + 1 }}</td>
                            <td class="col-tgl-wr">{{ \Carbon\Carbon::parse($item->tgl)->format('d-m-Y')}}</td>
                            <td class="col-agenda-wr">{{ $item->agenda }}</td>
                            <td class="col-uic-wr">{{ $item->uic }}</td>
                            <td class="col-peserta-wr">{{ $item->peserta }}</td>
                            <td class="col-pembahasan-wr">{!! nl2br(e($item->pembahasan)) !!}</td>
                            <td class="col-ac-wr">
                                @foreach($item->actionPlans as $plan)
                                <div class="action-plan-item">
                                    {{ $loop->iteration }}. {{ $plan->action_plan }}
                                </div>
                                @endforeach
                            </td>
                            <td class="col-sn-wr">{!! nl2br(e($item->support_needed)) !!}</td>
                            <td class="col-kompetitor-wr">{!! nl2br(e($item->info_kompetitor)) !!}</td>
                            <td class="col-jac-wr">{{ $item->jumlah_action_plan }}</td>
                            <td class="col-uap-wr">
                                @foreach($item->actionPlans as $plan)
                                <div class="action-plan-item">
                                    {{ $loop->iteration }}. {{ $plan->update_action_plan ?? '-' }}
                                </div>
                                @endforeach
                            </td>
                            <td class="col-sap-wr">
                                @foreach($item->actionPlans as $plan)
                                <div class="action-plan-item">
                                    {{ $loop->iteration }}.
                                    @if($plan->status_action_plan == 'Open')
                                    <span class="status-badge-wr status-open-wr">Open</span>
                                    @elseif($plan->status_action_plan == 'Progress')
                                    <span class="status-badge-wr status-progress-wr">Progress</span>
                                    @elseif($plan->status_action_plan == 'Eskalasi')
                                    <span class="status-badge-wr status-eskalasi-wr">Eskalasi</span>
                                    @elseif($plan->status_action_plan == 'Done')
                                    <span class="status-badge-wr status-closed-wr">Done</span>
                                    @else
                                    <span class="status-badge-wr status-default-wr">{{ $plan->status_action_plan }}</span>
                                    @endif
                                </div>
                                @endforeach
                            </td>
                            <td class="col-action-wr">
                                <div class="btn-group-horizontal-wr">
                                    <button type="button" class="btn-wr btn-sm btn-warning"
                                        onclick="openModal('editModal{{ $item->id }}')">
                                        Edit
                                    </button>
                                    <form action="{{ route('newwarroom.destroy', array_merge(['newwarroom' => $item->id], request()->only(['bulan','tahun','uic','search']))) }}"
                                        method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-wr btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>
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
</div>

@include('warroom.wrmodal')
@push('scripts')
<script src="{{ asset('js/tablescript.js') }}"></script>
@endpush
@endsection