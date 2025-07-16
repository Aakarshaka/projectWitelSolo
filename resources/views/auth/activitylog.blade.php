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

        .log-main-content {
            margin-left: 60px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .log-container {
            padding: 20px;
            width: 100%;
        }

        .log-header {
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

        .log-header h1 {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            min-width: 200px;
        }

        .log-stats-container {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .log-stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 8px 16px;
            text-align: center;
            min-width: 80px;
            flex-shrink: 0;
        }

        .log-stat-label {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 500;
        }

        .log-stat-value {
            font-size: 20px;
            font-weight: 700;
            margin-top: 2px;
        }

        .log-controls {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .log-filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            flex: 1;
        }

        .log-filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 120px;
            flex: 1;
        }

        .log-filter-label {
            font-size: 12px;
            font-weight: 500;
            color: #6d5671;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .log-filter-select {
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

        .log-filter-select:focus {
            outline: none;
            border-color: #4a0e4e;
            box-shadow: 0 0 0 3px rgba(74, 14, 78, 0.1);
        }

        .log-filter-btn {
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

        .log-filter-btn:hover {
            background: #3a0b3e;
        }

        .log-search-form {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .log-search-form .log-search-box {
            flex: 1;
            min-width: 200px;
            padding: 8px 12px;
            border: 1px solid #e6e1e8;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .log-search-form .log-search-box:focus {
            outline: none;
            border-color: #4a0e4e;
            box-shadow: 0 0 0 3px rgba(74, 14, 78, 0.1);
        }

        .log-table-container {
            position: relative;
            width: 100%;
            background: white;
            border-radius: 0px;
            border: 1px solid #e6e1e8;
            overflow: hidden;
        }

        .log-table-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: calc(100vh - 280px);
            position: relative;
        }

        .log-table-wrapper::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .log-table-wrapper::-webkit-scrollbar-track {
            background: #f8f6f9;
        }

        .log-table-wrapper::-webkit-scrollbar-thumb {
            background: #4a0e4e;
            border-radius: 4px;
        }

        .log-table-wrapper::-webkit-scrollbar-thumb:hover {
            background: #3a0b3e;
        }

        .log-table-wrapper::-webkit-scrollbar-corner {
            background: #f8f6f9;
        }

        .log-table {
            border-collapse: collapse;
            font-size: 13px;
            table-layout: fixed;
            width: 1200px;
            min-width: 100%;
        }

        .log-table th.log-col-no,
        .log-table td.log-col-no {
            width: 50px;
            min-width: 50px;
            max-width: 50px;
        }

        .log-table th.log-col-time,
        .log-table td.log-col-time {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
        }

        .log-table th.log-col-user,
        .log-table td.log-col-user {
            width: 150px;
            min-width: 150px;
            max-width: 150px;
        }

        .log-table th.log-col-action,
        .log-table td.log-col-action {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
        }

        .log-table th.log-col-data,
        .log-table td.log-col-data {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
        }

        .log-table th.log-col-description,
        .log-table td.log-col-description {
            width: 300px;
            min-width: 300px;
            max-width: 300px;
        }

        .log-table th.log-col-changes,
        .log-table td.log-col-changes {
            width: 150px;
            min-width: 150px;
            max-width: 150px;
        }

        .log-table th {
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

        .log-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #f2e8f3;
            vertical-align: middle;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        .log-table td.log-col-no,
        .log-table td.log-col-time,
        .log-table td.log-col-user,
        .log-table td.log-col-action,
        .log-table td.log-col-data,
        .log-table td.log-col-changes {
            text-align: center;
        }

        .log-table td.log-col-description {
            text-align: left;
        }

        .log-table tr:hover {
            background: #faf8fb;
        }

        .log-table tr:last-child td {
            border-bottom: none;
        }

        .log-user-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .log-user-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 12px;
        }

        .log-time-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .log-time-date {
            font-weight: 600;
            color: #2c3e50;
            font-size: 11px;
        }

        .log-time-hour {
            font-size: 10px;
            color: #6c757d;
        }

        .log-data-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .log-data-model {
            font-weight: 600;
            color: #495057;
            font-size: 11px;
        }

        .log-data-id {
            font-size: 10px;
            color: #6c757d;
        }

        .log-badge {
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

        .log-badge-create {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .log-badge-update {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }

        .log-badge-delete {
            background: linear-gradient(135deg, #dc3545 0%, #e91e63 100%);
            color: white;
        }

        .log-btn-detail {
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

        .log-btn-detail:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 123, 255, 0.4);
            color: white;
        }

        .log-no-changes {
            color: #6c757d;
            font-style: italic;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .log-empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .log-empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .log-scroll-hint {
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

        .log-footer {
            text-align: center;
            font-size: 15px;
            color: #6d5671;
            margin-top: 10px;
            padding: 10px 0;
        }

        /* Modal Styles */
        .log-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .log-modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border: none;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: log-modalFadeIn 0.3s ease;
        }

        @keyframes log-modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .log-modal-header {
            background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px 12px 0 0;
        }

        .log-modal-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .log-modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .log-modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .log-modal-body {
            padding: 30px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .log-modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .log-modal-body::-webkit-scrollbar-track {
            background: #f8f6f9;
        }

        .log-modal-body::-webkit-scrollbar-thumb {
            background: #4a0e4e;
            border-radius: 4px;
        }

        .log-changes-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .log-changes-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
        }

        .log-changes-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }

        .log-changes-table tr:last-child td {
            border-bottom: none;
        }

        .log-changes-table tr:hover {
            background: #f8f9fa;
        }

        .log-field-name {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
        }

        .log-field-value {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 12px;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            border-left: 4px solid #dee2e6;
            max-width: 300px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .log-field-value.log-old-value {
            border-left-color: #dc3545;
            background: #f8d7da;
        }

        .log-field-value.log-new-value {
            border-left-color: #28a745;
            background: #d4edda;
        }

        .log-info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .log-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .log-info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .log-info-label {
            font-size: 12px;
            font-weight: 500;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .log-info-value {
            font-size: 14px;
            font-weight: 600;
            color: #495057;
        }

        .log-no-changes-modal {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .log-no-changes-modal i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Mobile responsive */
        @media (max-width: 800px) {
            .log-main-content {
                margin-left: 0;
                margin-bottom: 60px;
            }

            .log-container {
                padding: 10px;
            }

            .log-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                margin: -10px -10px 15px -10px;
                padding: 15px;
            }

            .log-header h1 {
                font-size: 24px;
                min-width: auto;
            }

            .log-stats-container {
                justify-content: center;
                width: 100%;
            }

            .log-stat-card {
                min-width: 70px;
                padding: 6px 12px;
            }

            .log-stat-label {
                font-size: 10px;
            }

            .log-stat-value {
                font-size: 16px;
            }

            .log-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .log-filters {
                flex-direction: column;
                gap: 8px;
            }

            .log-filter-group {
                min-width: auto;
            }

            .log-filter-btn {
                align-self: center;
                width: 100%;
                max-width: 200px;
            }

            .log-search-form {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
                gap: 8px;
            }

            .log-search-form .log-search-box {
                width: 100%;
                min-width: auto;
            }

            .log-search-form .log-filter-btn {
                width: 100%;
                max-width: 200px;
                align-self: center;
            }

            .log-table {
                font-size: 11px;
            }

            .log-table th,
            .log-table td {
                padding: 8px 6px;
                font-size: 11px;
            }

            .log-table th {
                font-size: 10px;
            }

            .log-scroll-hint {
                display: block;
            }

            .log-modal-content {
                width: 95%;
                margin: 10% auto;
            }

            .log-modal-header {
                padding: 15px 20px;
            }

            .log-modal-title {
                font-size: 18px;
            }

            .log-modal-body {
                padding: 20px;
            }

            .log-info-grid {
                grid-template-columns: 1fr;
            }

            .log-changes-table {
                font-size: 12px;
            }

            .log-changes-table th,
            .log-changes-table td {
                padding: 10px;
            }

            .log-field-value {
                font-size: 11px;
                max-width: 100%;
            }
        }
    </style>
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
                        <p class="mb-0" style="opacity: 0.75; font-size: 14px;">Pantau semua aktivitas sistem secara
                            real-time</p>
                    </div>
                </div>
                <div class="log-stats-container">
                    <div class="log-stat-card">
                        <div class="log-stat-label">Create</div>
                        <div class="log-stat-value">{{ $logs->where('action', 'create')->count() }}</div>
                    </div>
                    <div class="log-stat-card">
                        <div class="log-stat-label">Update</div>
                        <div class="log-stat-value">{{ $logs->where('action', 'update')->count() }}</div>
                    </div>
                    <div class="log-stat-card">
                        <div class="log-stat-label">Delete</div>
                        <div class="log-stat-value">{{ $logs->where('action', 'delete')->count() }}</div>
                    </div>
                    <div class="log-stat-card">
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

            <div class="log-footer">Powered by <strong>GIAT CORE</strong></div>
        </div>
    </div>

    <!-- Modal for Changes Detail -->
@foreach($logs as $log)
    <div id="modal-{{ $log->id }}" class="log-modal">
        <div class="log-modal-content">
            <div class="log-modal-header">
                <h3 class="log-modal-title">
                    <i class="fas fa-info-circle"></i>
                    Detail Perubahan - {{ ucfirst($log->action) }}
                </h3>
                <button class="log-modal-close" onclick="closeModal({{ $log->id }})">&times;</button>
            </div>
            <div class="log-modal-body">
                <div class="log-info-section">
                    <div class="log-info-grid">
                        <div class="log-info-item">
                            <span class="log-info-label">Waktu</span>
                            <span class="log-info-value">{{ $log->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="log-info-item">
                            <span class="log-info-label">User</span>
                            <span class="log-info-value">{{ $log->user->name ?? 'System' }}</span>
                        </div>
                        <div class="log-info-item">
                            <span class="log-info-label">Action</span>
                            <span class="log-info-value action-{{ strtolower($log->action) }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </div>
                        <div class="log-info-item">
                            <span class="log-info-label">Model</span>
                            <span class="log-info-value">{{ class_basename($log->model_type) }}</span>
                        </div>
                        <div class="log-info-item">
                            <span class="log-info-label">Model ID</span>
                            <span class="log-info-value">#{{ $log->model_id }}</span>
                        </div>
                        <div class="log-info-item">
                            <span class="log-info-label">Deskripsi</span>
                            <span class="log-info-value">{{ $log->description }}</span>
                        </div>
                    </div>
                </div>

                @php
                    $allFields = [];
                    $changes = $log->changes;
                    
                    if ($changes && is_array($changes)) {
                        // Untuk action CREATE
                        if ($log->action === 'create') {
                            foreach ($changes as $field => $change) {
                                $allFields[$field] = [
                                    'old' => null,
                                    'new' => $change['new'] ?? $change ?? null
                                ];
                            }
                        }
                        // Untuk action UPDATE
                        elseif ($log->action === 'update') {
                            foreach ($changes as $field => $change) {
                                if (is_array($change) && isset($change['old']) && isset($change['new'])) {
                                    $allFields[$field] = [
                                        'old' => $change['old'],
                                        'new' => $change['new']
                                    ];
                                } else {
                                    $allFields[$field] = [
                                        'old' => $change['old'] ?? null,
                                        'new' => $change['new'] ?? $change ?? null
                                    ];
                                }
                            }
                        }
                        // Untuk action DELETE
                        elseif ($log->action === 'delete') {
                            foreach ($changes as $field => $change) {
                                $allFields[$field] = [
                                    'old' => $change['old'] ?? $change ?? null,
                                    'new' => null
                                ];
                            }
                        }
                    }
                @endphp

                @if(!empty($allFields))
                    <div class="log-changes-section">
                        <h4 class="log-changes-title">
                            @if($log->action === 'create')
                                <i class="fas fa-plus-circle text-success"></i> Data yang Ditambahkan
                            @elseif($log->action === 'update')
                                <i class="fas fa-edit text-warning"></i> Data yang Diubah
                            @elseif($log->action === 'delete')
                                <i class="fas fa-trash text-danger"></i> Data yang Dihapus
                            @endif
                        </h4>

                        <div class="log-changes-container">
                            <table class="log-changes-table">
                                <thead>
                                    <tr>
                                        <th width="25%">Field</th>
                                        <th width="37.5%">
                                            @if($log->action === 'create')
                                                Sebelum (Kosong)
                                            @endif
                                        </th>
                                        <th width="37.5%">
                                            @if($log->action === 'delete')
                                                Setelah (Dihapus)
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allFields as $field => $values)
                                        <tr>
                                            <td class="log-field-name">
                                                <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong>
                                            </td>
                                            <td class="log-field-value-cell">
                                                @if($log->action === 'create')
                                                    <div class="log-field-value log-empty-value">
                                                        <i class="fas fa-minus"></i>
                                                        <span class="text-muted">Kosong</span>
                                                    </div>
                                                @else
                                                    @if($values['old'] !== null)
                                                        <div class="log-field-value log-old-value">
                                                            @if(is_array($values['old']))
                                                                <pre>{{ json_encode($values['old'], JSON_PRETTY_PRINT) }}</pre>
                                                            @elseif(is_bool($values['old']))
                                                                <span class="badge badge-{{ $values['old'] ? 'success' : 'secondary' }}">
                                                                    {{ $values['old'] ? 'True' : 'False' }}
                                                                </span>
                                                            @elseif($values['old'] === '')
                                                                <span class="text-muted">Kosong</span>
                                                            @else
                                                                <span>{{ $values['old'] }}</span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="log-field-value-cell">
                                                @if($log->action === 'delete')
                                                    <div class="log-field-value log-deleted-value">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="text-muted">Dihapus</span>
                                                    </div>
                                                @else
                                                    @if($values['new'] !== null)
                                                        <div class="log-field-value log-new-value">
                                                            @if(is_array($values['new']))
                                                                <pre>{{ json_encode($values['new'], JSON_PRETTY_PRINT) }}</pre>
                                                            @elseif(is_bool($values['new']))
                                                                <span class="badge badge-{{ $values['new'] ? 'success' : 'secondary' }}">
                                                                    {{ $values['new'] ? 'True' : 'False' }}
                                                                </span>
                                                            @elseif($values['new'] === '')
                                                                <span class="text-muted">Kosong</span>
                                                            @else
                                                                <span>{{ $values['new'] }}</span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="log-no-changes-modal">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Tidak ada perubahan data yang tercatat</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach

<script>
    function openModal(logId) {
        const modal = document.getElementById('modal-' + logId);
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }
    }

    function closeModal(logId) {
        const modal = document.getElementById('modal-' + logId);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore background scroll
        }
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        if (event.target.classList.contains('log-modal')) {
            event.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const visibleModals = document.querySelectorAll('.log-modal[style*="display: block"]');
            visibleModals.forEach(modal => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        }
    });
</script>
@endsection