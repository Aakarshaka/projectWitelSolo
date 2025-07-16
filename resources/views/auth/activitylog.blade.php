@extends('layouts.layout')

@section('title', 'ACTIVITY LOG')

@section('content')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Poppins", sans-serif;
        background-color: #f8f6f9;
    }

    .main-content {
        margin-left: 60px;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    .container {
        padding: 20px;
        width: 100%;
    }

    .header {
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

    .header h1 {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.5px;
        min-width: 200px;
    }

    .stats-container {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 8px 16px;
        text-align: center;
        min-width: 80px;
        flex-shrink: 0;
    }

    .stat-label {
        font-size: 12px;
        opacity: 0.9;
        font-weight: 500;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        margin-top: 2px;
    }

    .controls {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filters {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 120px;
        flex: 1;
    }

    .filter-label {
        font-size: 12px;
        font-weight: 500;
        color: #6d5671;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-select {
        padding: 8px 12px;
        border: 1px solid #e6e1e8;
        border-radius: 6px;
        background: white;
        font-size: 14px;
        color: #2a1b2d;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .filter-select:focus {
        outline: none;
        border-color: #4a0e4e;
        box-shadow: 0 0 0 3px rgba(74, 14, 78, 0.1);
    }

    .filter-btn {
        background: #4a0e4e;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        align-self: flex-end;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .filter-btn:hover {
        background: #3a0b3e;
    }

    .search-form {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-form .search-box {
        flex: 1;
        min-width: 200px;
        padding: 8px 12px;
        border: 1px solid #e6e1e8;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-form .search-box:focus {
        outline: none;
        border-color: #4a0e4e;
        box-shadow: 0 0 0 3px rgba(74, 14, 78, 0.1);
    }

    .table-container-sn {
        position: relative;
        width: 100%;
        background: white;
        border-radius: 0px;
        border: 1px solid #e6e1e8;
        overflow: hidden;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        overflow-y: auto;
        max-height: calc(100vh - 280px);
        position: relative;
    }

    .table-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: #f8f6f9;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background: #4a0e4e;
        border-radius: 4px;
    }

    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #3a0b3e;
    }

    .table-wrapper::-webkit-scrollbar-corner {
        background: #f8f6f9;
    }

    .table {
        border-collapse: collapse;
        font-size: 13px;
        table-layout: fixed;
        width: 1200px;
        min-width: 100%;
    }

    .table th.col-no,
    .table td.col-no {
        width: 50px;
        min-width: 50px;
        max-width: 50px;
    }

    .table th.col-time,
    .table td.col-time {
        width: 120px;
        min-width: 120px;
        max-width: 120px;
    }

    .table th.col-user,
    .table td.col-user {
        width: 150px;
        min-width: 150px;
        max-width: 150px;
    }

    .table th.col-action,
    .table td.col-action {
        width: 100px;
        min-width: 100px;
        max-width: 100px;
    }

    .table th.col-data,
    .table td.col-data {
        width: 120px;
        min-width: 120px;
        max-width: 120px;
    }

    .table th.col-description,
    .table td.col-description {
        width: 300px;
        min-width: 300px;
        max-width: 300px;
    }

    .table th.col-changes,
    .table td.col-changes {
        width: 150px;
        min-width: 150px;
        max-width: 150px;
    }

    .table th {
        position: sticky;
        top: 0;
        background: #a8a8a9;
        color: #2a1b2d;
        font-weight: 600;
        padding: 12px 8px;
        text-align: center;
        border-bottom: 2px solid #e6e1e8;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
        z-index: 10;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table td {
        padding: 12px 8px;
        border-bottom: 1px solid #f2e8f3;
        vertical-align: middle;
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    .table td.col-no,
    .table td.col-time,
    .table td.col-user,
    .table td.col-action,
    .table td.col-data,
    .table td.col-changes {
        text-align: center;
    }

    .table td.col-description {
        text-align: left;
    }

    .table tr:hover {
        background: #faf8fb;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .avatar-sm {
        width: 30px;
        height: 30px;
        font-size: 12px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-right: 8px;
    }

    .user-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .user-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 12px;
    }

    .time-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .time-date {
        font-weight: 600;
        color: #2c3e50;
        font-size: 11px;
    }

    .time-hour {
        font-size: 10px;
        color: #6c757d;
    }

    .data-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .data-model {
        font-weight: 600;
        color: #495057;
        font-size: 11px;
    }

    .data-id {
        font-size: 10px;
        color: #6c757d;
    }

    .badge {
        font-size: 10px;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 12px;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    .badge-create {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .badge-update {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
    }

    .badge-delete {
        background: linear-gradient(135deg, #dc3545 0%, #e91e63 100%);
        color: white;
    }

    .btn-detail {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        color: white;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-detail:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.4);
        color: white;
    }

    .no-changes {
        color: #6c757d;
        font-style: italic;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .changes-container {
        margin-top: 8px;
    }

    .changes-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 8px;
        max-width: 300px;
    }

    .changes-card pre {
        background: transparent !important;
        border: none;
        font-size: 10px;
        line-height: 1.4;
        padding: 0;
        margin: 0;
        max-height: 150px;
        overflow-y: auto;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .scroll-hint {
        display: none;
        text-align: center;
        font-size: 12px;
        color: #6d5671;
        margin-bottom: 10px;
        padding: 8px;
        background: #f8f6f9;
        border-radius: 6px;
        border: 1px solid #e6e1e8;
    }

    .sn-footer {
        text-align: center;
        font-size: 15px;
        color: #6d5671;
        margin-top: 10px;
        padding: 10px 0;
    }

    /* Mobile responsive */
    @media (max-width: 800px) {
        .main-content {
            margin-left: 0;
            margin-bottom: 60px;
        }

        .container {
            padding: 10px;
        }

        .header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
            margin: -10px -10px 15px -10px;
            padding: 15px;
        }

        .header h1 {
            font-size: 24px;
            min-width: auto;
        }

        .stats-container {
            justify-content: center;
            width: 100%;
        }

        .stat-card {
            min-width: 70px;
            padding: 6px 12px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-value {
            font-size: 16px;
        }

        .controls {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .filters {
            flex-direction: column;
            gap: 8px;
        }

        .filter-group {
            min-width: auto;
        }

        .filter-btn {
            align-self: center;
            width: 100%;
            max-width: 200px;
        }

        .search-form {
            flex-direction: column;
            align-items: stretch;
            width: 100%;
            gap: 8px;
        }

        .search-form .search-box {
            width: 100%;
            min-width: auto;
        }

        .search-form .filter-btn {
            width: 100%;
            max-width: 200px;
            align-self: center;
        }

        .table {
            font-size: 11px;
        }

        .table th,
        .table td {
            padding: 8px 6px;
            font-size: 11px;
        }

        .table th {
            font-size: 10px;
        }

        .scroll-hint {
            display: block;
        }
    }
</style>

<div class="main-content">
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-history fa-2x"></i>
                </div>
                <div>
                    <h1>ACTIVITY LOG</h1>
                    <p class="mb-0" style="opacity: 0.75; font-size: 14px;">Pantau semua aktivitas sistem secara
                        real-time</p>
                </div>
            </div>
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-label">Create</div>
                    <div class="stat-value">{{ $logs->where('action', 'create')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Update</div>
                    <div class="stat-value">{{ $logs->where('action', 'update')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Delete</div>
                    <div class="stat-value">{{ $logs->where('action', 'delete')->count() }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total</div>
                    <div class="stat-value">{{ $logs->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Controls Section -->
        <div class="controls">
            <div class="filters">
                <form method="GET" action="{{ route('activity-log.index') }}" class="filters d-flex flex-wrap gap-3">
                    <!-- Filter Action -->
                    <div class="filter-group">
                        <label class="filter-label">Action</label>
                        <select class="filter-select" name="action">
                            <option value="">All Actions</option>
                            <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                            <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                            <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                        </select>
                    </div>

                    <!-- Filter Bulan -->
                    <div class="filter-group">
                        <label class="filter-label">Bulan</label>
                        <select class="filter-select" name="bulan">
                            <option value="">Semua Bulan</option>
                            @php
                            $nama_bulan = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                            ];
                            @endphp
                            @foreach($nama_bulan as $num => $nama)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Model -->
                    <div class="filter-group">
                        <label class="filter-label">Model</label>
                        <select class="filter-select" name="model_type">
                            <option value="">All Models</option>
                            @php
                            $modelLabels = [
                            'Supportneeded' => 'Support Needed',
                            'Newwarroom' => 'Warroom',
                            'newwarroom' => 'Warroom',
                            'NewWarroom' => 'Warroom',
                            'supportneeded' => 'Support Needed',
                            'SupportNeeded' => 'Support Needed',
                            ];
                            @endphp
                            @foreach($models as $model)
                            @php $shortName = class_basename($model); @endphp
                            <option value="{{ $model }}" {{ request('model_type') == $model ? 'selected' : '' }}>
                                {{ $modelLabels[$shortName] ?? $shortName }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Filter -->
                    <button type="submit" class="filter-btn">FILTER</button>
                </form>

                <!-- Form Search -->
                <form method="GET" action="{{ route('activity-log.index') }}"
                    class="search-form d-flex align-items-end gap-2 mt-3">
                    <input type="text" name="description" class="search-box" placeholder="Search description..."
                        value="{{ request('description') }}">
                    <button type="submit" class="filter-btn">SEARCH</button>
                </form>
            </div>
        </div>


        <div class="scroll-hint">
            ← Geser ke kiri/kanan untuk melihat semua Kolom →
        </div>

        <!-- Activity Table -->
        <div class="table-container-sn">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-time">
                                <i class="fas fa-clock me-1"></i>
                                Waktu
                            </th>
                            <th class="col-user">
                                <i class="fas fa-user me-1"></i>
                                User
                            </th>
                            <th class="col-action">
                                <i class="fas fa-cog me-1"></i>
                                Aksi
                            </th>
                            <th class="col-data">
                                <i class="fas fa-database me-1"></i>
                                Data
                            </th>
                            <th class="col-description">
                                <i class="fas fa-info-circle me-1"></i>
                                Deskripsi
                            </th>
                            <th class="col-changes">
                                <i class="fas fa-code me-1"></i>
                                Perubahan
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td class="col-no">
                                <span style="font-weight: 600; color: #6c757d;">{{ $loop->iteration }}</span>
                            </td>
                            <td class="col-time">
                                <div class="time-info">
                                    <span class="time-date">{{ $log->created_at->format('d M Y') }}</span>
                                    <span class="time-hour">{{ $log->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="col-user">
                                <div class="user-info">
                                    <div class="avatar-sm">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="user-name">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="col-action">
                                @php
                                $badgeClass = match ($log->action) {
                                'create' => 'badge-create',
                                'update' => 'badge-update',
                                'delete' => 'badge-delete',
                                default => 'bg-secondary'
                                };
                                $actionIcons = [
                                'create' => 'plus-circle',
                                'update' => 'edit',
                                'delete' => 'trash'
                                ];
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    <i class="fas fa-{{ $actionIcons[$log->action] ?? 'question' }}"></i>
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="col-data">
                                @php
                                $modelLabels = [
                                'Supportneeded' => 'Support Needed',
                                'Newwarroom' => 'Warroom',
                                'newwarroom' => 'Warroom',
                                'NewWarroom' => 'Warroom',
                                'supportneeded' => 'Support Needed',
                                'SupportNeeded' => 'Support Needed',
                                ];
                                $shortModel = class_basename($log->model_type);
                                $displayModel = $modelLabels[$shortModel] ?? $shortModel;
                                @endphp
                                <div class="data-info">
                                    <span class="data-model">{{ $displayModel }}</span>
                                    <span class="data-id">#{{ $log->model_id }}</span>
                                </div>
                            </td>
                            <td class="col-description">
                                <span style="color: #495057; font-size: 12px;">{{ $log->description }}</span>
                            </td>
                            <td class="col-changes">
                                @if ($log->changes)
                                <button class="btn-detail" data-bs-toggle="collapse"
                                    data-bs-target="#changes-{{ $log->id }}" aria-expanded="false">
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                                <div class="collapse changes-container" id="changes-{{ $log->id }}">
                                    <div class="changes-card">
                                        <pre>{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                                @else
                                <span class="no-changes">
                                    <i class="fas fa-minus"></i>
                                    No changes
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>Belum ada aktivitas tercatat</h5>
                                    <p class="mb-0">Aktivitas sistem akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="sn-footer">Powered by <strong>GIAT CORE</strong></div>
    </div>
</div>
@endsection