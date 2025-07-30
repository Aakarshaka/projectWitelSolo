@extends('layouts.layout')

@section('title', 'ACTIVITY LOG')

@section('content')
<div class="log-main-content">
    <div class="log-container">
        <!-- Header Section -->
            <div class="log-header">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-history fa-2x"></i>
                    </div>
                    <div>
                        <h1>ACTIVITY LOG</h1>
                        <p class="mb-0" style="opacity: 0.75; font-size: 14px;">Pantau semua aktivitas sistem secara real-time</p>
                    </div>
                </div>
                <div class="log-stats-container">
                    <div class="log-stat-card" data-action="create">
                        <div class="log-stat-label">Create</div>
                        <div class="log-stat-value">{{ $logs->where('action', 'create')->count() }}</div>
                    </div>
                    <div class="log-stat-card" data-action="update">
                        <div class="log-stat-label">Update</div>
                        <div class="log-stat-value">{{ $logs->where('action', 'update')->count() }}</div>
                    </div>
                    <div class="log-stat-card" data-action="delete">
                        <div class="log-stat-label">Delete</div>
                        <div class="log-stat-value">{{ $logs->where('action', 'delete')->count() }}</div>
                    </div>
                    <div class="log-stat-card" data-action="total">
                        <div class="log-stat-label">Total</div>
                        <div class="log-stat-value">{{ $logs->count() }}</div>
                    </div>
                </div>
            </div>

        <!-- Controls Section -->
        <div class="log-controls">
            <div class="log-filters">
                <form method="GET" action="{{ route('activity-log.index') }}"
                    class="log-filters d-flex flex-wrap gap-3">
                    <!-- Filter Action -->
                    <div class="log-filter-group">
                        <label class="log-filter-label">Action</label>
                        <select class="log-filter-select" name="action">
                            <option value="">All Actions</option>
                            <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                            <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                            <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                        </select>
                    </div>

                    <!-- Filter Bulan -->
                    <div class="log-filter-group">
                        <label class="log-filter-label">Bulan</label>
                        <select class="log-filter-select" name="bulan">
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
                    <div class="log-filter-group">
                        <label class="log-filter-label">Model</label>
                        <select class="log-filter-select" name="model_type">
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
                    <button type="submit" class="log-filter-btn">FILTER</button>
                </form>

                <!-- Form Search -->
                <form method="GET" action="{{ route('activity-log.index') }}"
                    class="log-search-form d-flex align-items-end gap-2 mt-3">
                    <input type="text" name="description" class="log-search-box" placeholder="Search description..."
                        value="{{ request('description') }}">
                    <button type="submit" class="log-filter-btn">SEARCH</button>
                </form>
            </div>
        </div>

        <div class="log-scroll-hint">
            ← Geser ke kiri/kanan untuk melihat semua Kolom →
        </div>

        <!-- Activity Table -->
        <div class="log-table-container">
            <div class="log-table-wrapper">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th class="log-col-no">No</th>
                            <th class="log-col-time">
                                <i class="fas fa-clock me-1"></i>
                                Waktu
                            </th>
                            <th class="log-col-user">
                                <i class="fas fa-user me-1"></i>
                                User
                            </th>
                            <th class="log-col-action">
                                <i class="fas fa-cog me-1"></i>
                                Aksi
                            </th>
                            <th class="log-col-data">
                                <i class="fas fa-database me-1"></i>
                                Data
                            </th>
                            <th class="log-col-description">
                                <i class="fas fa-info-circle me-1"></i>
                                Deskripsi
                            </th>
                            <th class="log-col-changes">
                                <i class="fas fa-code me-1"></i>
                                Perubahan
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td class="log-col-no">
                                <span style="font-weight: 600; color: #6c757d;">{{ $loop->iteration }}</span>
                            </td>
                            <td class="log-col-time">
                                <div class="log-time-info">
                                    <span class="log-time-date">{{ $log->created_at->format('d M Y') }}</span>
                                    <span class="log-time-hour">{{ $log->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="log-col-user">
                                <div class="log-user-info">
                                    <span class="log-user-name">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="log-col-action">
                                @php
                                $badgeClass = match ($log->action) {
                                'create' => 'log-badge-create',
                                'update' => 'log-badge-update',
                                'delete' => 'log-badge-delete',
                                default => 'bg-secondary'
                                };
                                $actionIcons = [
                                'create' => 'plus-circle',
                                'update' => 'edit',
                                'delete' => 'trash'
                                ];
                                @endphp
                                <span class="log-badge {{ $badgeClass }}">
                                    <i class="fas fa-{{ $actionIcons[$log->action] ?? 'question' }}"></i>
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="log-col-data">
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
                                <div class="log-data-info">
                                    <span class="log-data-model">{{ $displayModel }}</span>
                                    <span class="log-data-id">#{{ $log->model_id }}</span>
                                </div>
                            </td>
                            <td class="log-col-description">
                                <span style="color: #495057; font-size: 12px;">{{ $log->description }}</span>
                            </td>
                            <td class="log-col-changes">
                                @if ($log->changes)
                                <button class="log-btn-detail" onclick="openModal({{ $log->id }})">
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                                @else
                                <span class="log-no-changes">
                                    <i class="fas fa-minus"></i>
                                    No changes
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="log-empty-state">
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
    </div>
    <div class="log-footer">Powered by <strong>GIAT CORE</strong></div>
</div>
@include('log.logmodal')
@endsection