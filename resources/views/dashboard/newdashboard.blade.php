@extends('layouts.layout')

@section('title', 'Dashboard')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%);
            color: white;
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(180deg);
            }
        }

        .dashboard-header h2 {
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .filter-form {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .year-selector,
        .month-selector {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            backdrop-filter: blur(15px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1rem;
            min-width: 120px;
        }

        .year-selector:hover,
        .month-selector:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .year-selector:focus,
        .month-selector:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3);
        }

        .year-selector option,
        .month-selector option {
            background: #764ba2;
            color: white;
            padding: 0.5rem;
        }

        .stat-card {
            border: none;
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            height: 160px;
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .stat-card .card-body {
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card .card-body::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -60%;
            width: 120%;
            height: 120%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .stat-content {
            flex: 1;
            z-index: 2;
            position: relative;
        }

        .stat-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            opacity: 0.9;
        }

        .stat-card .card-text {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            line-height: 1;
        }

        .stat-card.primary {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }

        .stat-card.success {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #0EA5E9 0%, #0284C7 100%);
        }

        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .chart-container h3 {
            color: #1F2937;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-container h3 i {
            color: #667eea;
        }

        .dashboard-container {
            padding: 2rem;
            margin-left: 0;
            max-width: 100%;
            background: #f8fafc;
            min-height: 100vh;
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(10px);
        }

        .icon-wrapper i {
            font-size: 1.5rem;
            color: white;
        }

        .container {
            max-width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .chart-wrapper {
            position: relative;
            height: 350px;
        }

        .chart-wrapper canvas {
            max-height: 350px !important;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .charts-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .completion-rate {
            font-size: 3rem;
            font-weight: 800;
            color: #059669;
            text-align: center;
            margin: 2rem 0;
        }

        .completion-rate small {
            font-size: 1rem;
            color: #6b7280;
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {

            .charts-grid,
            .charts-grid-3 {
                grid-template-columns: 1fr;
            }

            .filter-form {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.5rem;
            }

            .dashboard-header h2 {
                font-size: 1.8rem;
            }

            .dashboard-header .d-flex {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start !important;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
            }

            .stat-card {
                height: 140px;
            }
        }

        /* Animation for cards */
        .stat-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .stat-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <div class="container">
            <!-- Enhanced Header -->
            <div class="dashboard-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
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
                                '12' => 'Desember'
                            ];
                        @endphp
                        <h2><i class="fas fa-chart-line me-2"></i>Dashboard Overview</h2>
                        <p>
                            Menampilkan data untuk
                            {{ $bulan ? $nama_bulan[str_pad($bulan, 2, '0', STR_PAD_LEFT)] . ' ' : '' }}
                            {{ $tahun }}
                        </p>
                    </div>
                    <form method="GET" class="filter-form">
                        <select name="bulan" onchange="this.form.submit()" class="month-selector">
                            <option value="">Semua Bulan</option>
                            @php
                                $monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            @endphp
                            @foreach ($monthNames as $index => $name)
                                <option value="{{ $index + 1 }}" {{ ($bulan == $index + 1) ? 'selected' : '' }}>{{ $name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="tahun" onchange="this.form.submit()" class="year-selector">
                            @for ($y = 2023; $y <= 2030; $y++)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
            </div>

            <!-- Enhanced Stat Cards with Grid -->
            <div class="stats-grid">
                <div class="card stat-card primary text-white">
                    <div class="card-body">
                        <div class="stat-content">
                            <h5 class="card-title">Total Agenda</h5>
                            <p class="card-text">{{ number_format($total_agenda) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card success text-white">
                    <div class="card-body">
                        <div class="stat-content">
                            <h5 class="card-title">Action Plan</h5>
                            <p class="card-text">{{ number_format($total_action_plan) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card warning text-white">
                    <div class="card-body">
                        <div class="stat-content">
                            <h5 class="card-title">Eskalasi</h5>
                            <p class="card-text">{{ number_format($total_eskalasi) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card danger text-white">
                    <div class="card-body">
                        <div class="stat-content">
                            <h5 class="card-title">Closed</h5>
                            <p class="card-text">{{ number_format($total_closed) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card info text-white">
                    <div class="card-body">
                        <div class="stat-content">
                            <h5 class="card-title">Completion Rate</h5>
                            <p class="card-text">{{ $completion_rate }}%</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Charts with Grid -->
            <div class="charts-grid">
                <div class="chart-container">
                    <h3>
                        <i class="fas fa-chart-bar"></i>
                        @if($bulan)
                            Agenda per Hari
                        @else
                            Agenda per Bulan
                        @endif
                    </h3>
                    <div class="chart-wrapper">
                        <canvas id="agendaChart"></canvas>
                    </div>
                </div>

                <div class="chart-container">
                    <h3><i class="fas fa-chart-pie"></i>Status Distribution</h3>
                    <div class="chart-wrapper">
                        <canvas id="statusPieChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Additional Charts -->
            <div class="charts-grid">
                <div class="chart-container">
                    <h3>
                        <i class="fas fa-line-chart"></i>
                        @if($bulan)
                            Trend Action Plan per Hari
                        @else
                            Trend Action Plan per Bulan
                        @endif
                    </h3>
                    <div class="chart-wrapper">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                @if($bulan && count($weekly_data) > 0)
                    <div class="chart-container">
                        <h3><i class="fas fa-calendar-week"></i>Progress Mingguan</h3>
                        <div class="chart-wrapper">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                @endif
            </div>

            @if(count($top_issues) > 0)
                <div class="chart-container">
                    <h3><i class="fas fa-list-ol"></i>Top 5 Issues/Categories</h3>
                    <div class="chart-wrapper">
                        <canvas id="topIssuesChart"></canvas>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Chart JS -->
    <script>
        const bulanLabels = {!! json_encode($bulan_labels) !!};
        const agendaData = {!! json_encode($jumlah_agenda_per_bulan) !!};
        const statusLabels = {!! json_encode($status_labels) !!};
        const statusData = {!! json_encode($status_counts) !!};
        const trendData = {!! json_encode($trend_action_plan) !!};
        const weeklyData = {!! json_encode($weekly_data) !!};
        const topIssues = {!! json_encode($top_issues) !!};

        // Chart colors
        const colors = {
            primary: '#4F46E5',
            success: '#059669',
            warning: '#F59E0B',
            danger: '#EF4444',
            info: '#0EA5E9'
        };

        // Enhanced Bar Chart - Agenda
        new Chart(document.getElementById('agendaChart'), {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets: [{
                    label: 'Jumlah Agenda',
                    data: agendaData,
                    backgroundColor: function (context) {
                        const chart = context.chart;
                        const { ctx, chartArea } = chart;
                        if (!chartArea) return null;
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, '#667eea');
                        gradient.addColorStop(1, '#764ba2');
                        return gradient;
                    },
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(102, 126, 234, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280', font: { size: 12, weight: '500' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            precision: 0,
                            color: '#6b7280',
                            font: { size: 12, weight: '500' }
                        }
                    }
                },
                animation: { duration: 1000, easing: 'easeInOutQuart' }
            }
        });

        // Enhanced Pie Chart - Status Distribution
        new Chart(document.getElementById('statusPieChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: [colors.primary, colors.success, colors.warning, colors.danger],
                    borderWidth: 0,
                    cutout: '65%',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#374151',
                            font: { size: 12, weight: '500' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(102, 126, 234, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.parsed / total) * 100);
                                return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                animation: { duration: 1000, easing: 'easeInOutQuart' }
            }
        });

        // Line Chart - Trend Action Plan
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [{
                    label: 'Action Plan',
                    data: trendData,
                    borderColor: colors.success,
                    backgroundColor: colors.success + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.success,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280', font: { size: 12, weight: '500' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            precision: 0,
                            color: '#6b7280',
                            font: { size: 12, weight: '500' }
                        }
                    }
                },
                animation: { duration: 1000, easing: 'easeInOutQuart' }
            }
        });

        // Weekly Chart (only if month filter is active)
        @if($bulan && count($weekly_data) > 0)
            new Chart(document.getElementById('weeklyChart'), {
                type: 'bar',
                data: {
                    labels: weeklyData.map(item => item.label),
                    datasets: [{
                        label: 'Jumlah Agenda',
                        data: weeklyData.map(item => item.count),
                        backgroundColor: function (context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, colors.info);
                            gradient.addColorStop(1, colors.info + '80');
                            return gradient;
                        },
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(14, 165, 233, 0.3)',
                            borderWidth: 1,
                            cornerRadius: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6b7280', font: { size: 12, weight: '500' } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                precision: 0,
                                color: '#6b7280',
                                font: { size: 12, weight: '500' }
                            }
                        }
                    },
                    animation: { duration: 1000, easing: 'easeInOutQuart' }
                }
            });
        @endif

        // Top Issues Chart (only if data exists)
        @if(count($top_issues) > 0)
            new Chart(document.getElementById('topIssuesChart'), {
                type: 'horizontalBar',
                data: {
                    labels: topIssues.map(item => item.kategori),
                    datasets: [{
                        label: 'Jumlah',
                        data: topIssues.map(item => item.total),
                        backgroundColor: function (context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(chartArea.left, 0, chartArea.right, 0);
                            gradient.addColorStop(0, colors.warning);
                            gradient.addColorStop(1, colors.warning + '60');
                            return gradient;
                        },
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(245, 158, 11, 0.3)',
                            borderWidth: 1,
                            cornerRadius: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                precision: 0,
                                color: '#6b7280',
                                font: { size: 12, weight: '500' }
                            }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { color: '#6b7280', font: { size: 12, weight: '500' } }
                        }
                    },
                    animation: { duration: 1000, easing: 'easeInOutQuart' }
                }
            });
        @endif

        // Priority Distribution Chart (only if priority data exists)
        @if(count($priority_counts) > 0 && array_sum($priority_counts) > 0)
            new Chart(document.getElementById('priorityChart'), {
                type: 'polarArea',
                data: {
                    labels: {!! json_encode($priority_labels) !!},
                    datasets: [{
                        data: {!! json_encode($priority_counts) !!},
                        backgroundColor: [
                            colors.danger + '80',
                            colors.warning + '80',
                            colors.success + '80'
                        ],
                        borderColor: [
                            colors.danger,
                            colors.warning,
                            colors.success
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: '#374151',
                                font: { size: 12, weight: '500' }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((context.parsed / total) * 100);
                                    return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            pointLabels: { color: '#6b7280', font: { size: 12, weight: '500' } },
                            ticks: {
                                color: '#6b7280',
                                font: { size: 10 },
                                backdropColor: 'transparent'
                            }
                        }
                    },
                    animation: { duration: 1000, easing: 'easeInOutQuart' }
                }
            });
        @endif

        // Add loading animation
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.stat-card');
            const charts = document.querySelectorAll('.chart-container');

            // Animate cards on load
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    card.style.transition = 'all 0.6s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });

            // Animate charts on load
            charts.forEach((chart, index) => {
                setTimeout(() => {
                    chart.style.opacity = '0';
                    chart.style.transform = 'translateY(20px)';
                    chart.style.transition = 'all 0.8s ease';

                    setTimeout(() => {
                        chart.style.opacity = '1';
                        chart.style.transform = 'translateY(0)';
                    }, 100);
                }, (index + cards.length) * 100);
            });
        });

        // Add hover effects for better interactivity
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-8px) scale(1.02)';
                this.style.boxShadow = '0 15px 35px rgba(0, 0, 0, 0.2)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.1)';
            });
        });

        // Add chart resize handler
        window.addEventListener('resize', function () {
            Chart.helpers.each(Chart.instances, function (instance) {
                instance.resize();
            });
        });

        // Add smooth transitions for form changes
        document.querySelectorAll('.year-selector, .month-selector').forEach(select => {
            select.addEventListener('change', function () {
                const loader = document.createElement('div');
                loader.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center';
                loader.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
                loader.style.zIndex = '9999';
                loader.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                document.body.appendChild(loader);
            });
        });
    </script>
@endsection