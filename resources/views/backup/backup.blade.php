@extends('layouts.layout')

@section('title', 'Dashboard')


@section('content')
<style>
    .dashboard-header {
            background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%) !important;
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

        .stat-card-d {
            border: none;
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            height: 160px;
            position: relative;
        }

        .stat-card-d:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .stat-card-d .card-body {
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card-d .card-body::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -60%;
            width: 120%;
            height: 120%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .stat-content-d {
            flex: 1;
            z-index: 2;
            position: relative;
        }

        .stat-card-d .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            opacity: 0.9;
        }

        .stat-card-d .card-text {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            line-height: 1;
        }

        .stat-card-d.primary {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }

        .stat-card-d.success {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .stat-card-d.warning {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }

        .stat-card-d.danger {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        }

        .stat-card-d.info {
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

        .container-d {
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

        .stats-grid-d {
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
        .stat-card-d {
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-card-d:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card-d:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card-d:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-card-d:nth-child(4) {
            animation-delay: 0.4s;
        }

        .stat-card-d:nth-child(5) {
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

        .section-header {
            margin: 2rem 0 1rem 0;
            padding: 1rem 0;
        }

        .section-header h3 {
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .section-divider {
            height: 3px;
            background: linear-gradient(135deg, #8b1538 0%, #4a0e4e 100%) !important;
            border-radius: 2px;
            margin-bottom: 1rem;
        }

        .stats-grid-d {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card-d {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .stat-card-d.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card-d.success { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); }
        .stat-card-d.warning { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
        .stat-card-d.danger { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
        .stat-card-d.info { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
        .stat-card-d.secondary { background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); }

        .stat-content-d {
            position: relative;
            z-index: 2;
        }

        .stat-content-d h5 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .stat-content-d p {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .icon-wrapper {
            position: absolute;
            top: 1rem;
            right: 1rem;
            opacity: 0.3;
            font-size: 2rem;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .chart-container h3 {
            color: #374151;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .chart-container h3 i {
            margin-right: 0.5rem;
            color: #667eea;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
        }

        .filter-form {
            display: flex;
            gap: 1rem;
        }

        .month-selector, .year-selector {
            padding: 0.5rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            color: #374151;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .month-selector:focus, .year-selector:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .dashboard-header h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            opacity: 0.9;
            margin: 0;
        }

        @media (max-width: 768px) {
            .stats-grid-d {
                grid-template-columns: 1fr;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-form {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
</style>
<div class="dashboard-container">
        <div class="container-d">
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
                        <h2>Dashboard Overview</h2>
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

            <!-- WAR ROOM SECTION -->
            <div class="section-header">
                <h3><i class="fas fa-sitemap me-2"></i>War Room Dashboard</h3>
                <div class="section-divider"></div>
            </div>

            <!-- War Room Stats Cards -->
            <div class="stats-grid-d">
                <div class="card stat-card-d primary text-white">
                    <div class="card-body">
                        <div class="stat-content-d">
                            <h5 class="card-title">Total Agenda</h5>
                            <p class="card-text">{{ number_format($total_agenda) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card-d success text-white">
                    <div class="card-body">
                        <div class="stat-content-d">
                            <h5 class="card-title">Action Plan</h5>
                            <p class="card-text">{{ number_format($total_action_plan) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card-d warning text-white">
                    <div class="card-body">
                        <div class="stat-content-d">
                            <h5 class="card-title">Eskalasi</h5>
                            <p class="card-text">{{ number_format($total_eskalasi) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card-d danger text-white">
                    <div class="card-body">
                        <div class="stat-content-d">
                            <h5 class="card-title">Closed</h5>
                            <p class="card-text">{{ number_format($total_closed) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card-d info text-white">
                    <div class="card-body">
                        <div class="stat-content-d">
                            <h5 class="card-title">Completion Rate</h5>
                            <p class="card-text">{{ $completion_rate }}%</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- War Room Charts -->
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

            <!-- Additional War Room Charts -->
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

            @if(count($priority_counts) > 0 && array_sum($priority_counts) > 0)
                <div class="chart-container">
                    <h3><i class="fas fa-exclamation-circle"></i>Priority Distribution</h3>
                    <div class="chart-wrapper">
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>
            @endif

            <!-- SUPPORT NEEDED SECTION -->
            <div class="section-header mt-5">
                <h3><i class="fas fa-hands-helping me-2"></i>Support Needed Dashboard</h3>
                <div class="section-divider"></div>
            </div>

            <!-- Support Needed Stats Cards -->
            <div class="stats-grid-d">
                <div class="card stat-card-d secondary text-white">
                    <div class="card-body">
                        <div class="stat-content-d">
                            <h5 class="card-title">Total Support</h5>
                            <p class="card-text">{{ number_format($total_support) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-life-ring"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card-d success text-white">
                    <div class="card-body">
                        <div class="stat-content-d">
                            <h5 class="card-title">Closed Support</h5>
                            <p class="card-text">{{ number_format($closed_support) }}</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card-d info text-white">
                    <div class="card-body">
                        <div class="stat-content-d">
                            <h5 class="card-title">Close Percentage</h5>
                            <p class="card-text">{{ $close_percentage }}%</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-percentage"></i>
                        </div>
                    </div>
                </div>

                <div class="card stat-card-d warning text-white">
                    <div class="card-body">
                        <div class="stat-content-d">
                            <h5 class="card-title">Avg Progress</h5>
                            <p class="card-text">{{ $avg_support_progress }}%</p>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Needed Charts -->
            <div class="charts-grid">
                <div class="chart-container">
                    <h3><i class="fas fa-chart-pie"></i>Support Status Distribution</h3>
                    <div class="chart-wrapper">
                        <canvas id="supportStatusChart"></canvas>
                    </div>
                </div>

                <div class="chart-container">
                    <h3><i class="fas fa-chart-bar"></i>Support Progress Overview</h3>
                    <div class="chart-wrapper">
                        <canvas id="supportProgressChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data from controller
        const bulanLabels = {!! json_encode($bulan_labels) !!};
        const agendaData = {!! json_encode($jumlah_agenda_per_bulan) !!};
        const statusLabels = {!! json_encode($status_labels) !!};
        const statusData = {!! json_encode($status_counts) !!};
        const trendData = {!! json_encode($trend_action_plan) !!};
        const weeklyData = {!! json_encode($weekly_data) !!};
        const topIssues = {!! json_encode($top_issues) !!};
        const priorityLabels = {!! json_encode($priority_labels) !!};
        const priorityData = {!! json_encode($priority_counts) !!};
        const supportStatusData = {!! json_encode($support_status_distribution) !!};
        const supportProgressData = {!! json_encode($support_progress_data ?? []) !!};

        // Chart colors matching CSS theme
        const colors = {
            primary: '#667eea',
            success: '#059669',
            warning: '#F59E0B',
            danger: '#EF4444',
            info: '#0EA5E9',
            secondary: '#8b5cf6'
        };

        // Common chart options
        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#374151',
                        font: { size: 12, weight: '500' },
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: colors.primary + '50',
                    borderWidth: 1,
                    cornerRadius: 12,
                    displayColors: false,
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 },
                    padding: 12
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        };

        // WAR ROOM CHARTS

        // Enhanced Bar Chart - Agenda
        const agendaChart = new Chart(document.getElementById('agendaChart'), {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets: [{
                    label: 'Jumlah Agenda',
                    data: agendaData,
                    backgroundColor: function (context) {
                        const chart = context.chart;
                        const { ctx, chartArea } = chart;
                        if (!chartArea) return colors.primary;

                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, colors.primary);
                        gradient.addColorStop(1, colors.primary + '80');
                        return gradient;
                    },
                    borderColor: colors.primary,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: colors.primary + 'CC',
                    hoverBorderColor: colors.primary
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    ...defaultOptions.plugins,
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 12, weight: '500' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            precision: 0,
                            color: '#6b7280',
                            font: { size: 12, weight: '500' }
                        }
                    }
                }
            }
        });

        // Enhanced Pie Chart - Status Distribution
        const statusPieChart = new Chart(document.getElementById('statusPieChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: [
                        colors.primary,
                        colors.success,
                        colors.warning,
                        colors.danger,
                        colors.info
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    cutout: '65%',
                    hoverOffset: 8,
                    hoverBorderWidth: 4
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    ...defaultOptions.plugins,
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
                        ...defaultOptions.plugins.tooltip,
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.parsed / total) * 100);
                                return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Line Chart - Trend Action Plan
        const trendChart = new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [{
                    label: 'Action Plan',
                    data: trendData,
                    borderColor: colors.success,
                    backgroundColor: function (context) {
                        const chart = context.chart;
                        const { ctx, chartArea } = chart;
                        if (!chartArea) return colors.success + '20';

                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, colors.success + '20');
                        gradient.addColorStop(1, colors.success + '10');
                        return gradient;
                    },
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.success,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBorderWidth: 4
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    ...defaultOptions.plugins,
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 12, weight: '500' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            precision: 0,
                            color: '#6b7280',
                            font: { size: 12, weight: '500' }
                        }
                    }
                }
            }
        });

        // Weekly Chart (only if month filter is active)
        @if($bulan && count($weekly_data) > 0)
            const weeklyChart = new Chart(document.getElementById('weeklyChart'), {
                type: 'bar',
                data: {
                    labels: weeklyData.map(item => item.label),
                    datasets: [{
                        label: 'Jumlah Agenda',
                        data: weeklyData.map(item => item.count),
                        backgroundColor: function (context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return colors.info;

                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, colors.info);
                            gradient.addColorStop(1, colors.info + '80');
                            return gradient;
                        },
                        borderColor: colors.info,
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    ...defaultOptions,
                    plugins: {
                        ...defaultOptions.plugins,
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: { size: 12, weight: '500' }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0,
                                color: '#6b7280',
                                font: { size: 12, weight: '500' }
                            }
                        }
                    }
                }
            });
        @endif

            // Top Issues Chart (only if data exists)
            @if(count($top_issues) > 0)
                const topIssuesChart = new Chart(document.getElementById('topIssuesChart'), {
                    type: 'bar',
                    data: {
                        labels: topIssues.map(item => item.kategori),
                        datasets: [{
                            label: 'Jumlah',
                            data: topIssues.map(item => item.total),
                            backgroundColor: function (context) {
                                const chart = context.chart;
                                const { ctx, chartArea } = chart;
                                if (!chartArea) return colors.warning;

                                const gradient = ctx.createLinearGradient(chartArea.left, 0, chartArea.right, 0);
                                gradient.addColorStop(0, colors.warning);
                                gradient.addColorStop(1, colors.warning + '60');
                                return gradient;
                            },
                            borderColor: colors.warning,
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        indexAxis: 'y',
                        plugins: {
                            ...defaultOptions.plugins,
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    precision: 0,
                                    color: '#6b7280',
                                    font: { size: 12, weight: '500' }
                                }
                            },
                            y: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: { size: 12, weight: '500' }
                                }
                            }
                        }
                    }
                });
            @endif

            // Priority Distribution Chart (only if priority data exists)
            @if(count($priority_counts) > 0 && array_sum($priority_counts) > 0)
                const priorityChart = new Chart(document.getElementById('priorityChart'), {
                    type: 'polarArea',
                    data: {
                        labels: priorityLabels,
                        datasets: [{
                            data: priorityData,
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
                            borderWidth: 2,
                            hoverBorderWidth: 3
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        plugins: {
                            ...defaultOptions.plugins,
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
                                ...defaultOptions.plugins.tooltip,
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
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)',
                                    circular: true
                                },
                                pointLabels: {
                                    color: '#6b7280',
                                    font: { size: 12, weight: '500' }
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: { size: 10 },
                                    backdropColor: 'transparent'
                                }
                            }
                        }
                    }
                });
            @endif

            // SUPPORT NEEDED CHARTS

            // Support Status Distribution Chart
            @if(count($support_status_distribution) > 0)
                const supportStatusChart = new Chart(document.getElementById('supportStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(supportStatusData),
                        datasets: [{
                            data: Object.values(supportStatusData),
                            backgroundColor: [
                                colors.primary,
                                colors.success,
                                colors.warning,
                                colors.danger,
                                colors.info,
                                colors.secondary
                            ],
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            cutout: '65%',
                            hoverOffset: 8,
                            hoverBorderWidth: 4
                        }]
                    },
                    options: {
                        ...defaultOptions,
                        plugins: {
                            ...defaultOptions.plugins,
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
                                ...defaultOptions.plugins.tooltip,
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((context.parsed / total) * 100);
                                        return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            @endif

            // Support Progress Chart - Fixed Version
            @if(isset($support_progress_data) && count($support_progress_data) > 0)
                // Pass data from PHP to JavaScript
                const supportProgressData = @json($support_progress_data);

                // Define colors if not already defined
                const colors = {
                    primary: '#3b82f6',
                    secondary: '#10b981',
                    tertiary: '#f59e0b',
                    quaternary: '#ef4444'
                };

                // Define default options if not already defined
                const defaultOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 12, weight: '500' }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 12
                        }
                    },
                    animation: {
                        duration: 800,
                        easing: 'easeInOutQuart'
                    }
                };

                // Check if canvas element exists
                const canvasElement = document.getElementById('supportProgressChart');
                if (canvasElement) {
                    const supportProgressChart = new Chart(canvasElement, {
                        type: 'bar',
                        data: {
                            labels: supportProgressData.map(item => item.label || item.range || 'Unknown'),
                            datasets: [{
                                label: 'Jumlah Support',
                                data: supportProgressData.map(item => item.count || 0),
                                backgroundColor: function (context) {
                                    const chart = context.chart;
                                    const { ctx, chartArea } = chart;
                                    if (!chartArea) return colors.secondary;

                                    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                    gradient.addColorStop(0, colors.secondary);
                                    gradient.addColorStop(1, colors.secondary + '80');
                                    return gradient;
                                },
                                borderColor: colors.secondary,
                                borderWidth: 2,
                                borderRadius: 8,
                                borderSkipped: false
                            }]
                        },
                        options: {
                            ...defaultOptions,
                            plugins: {
                                ...defaultOptions.plugins,
                                legend: { display: false }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        color: '#6b7280',
                                        font: { size: 12, weight: '500' }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                        drawBorder: false
                                    },
                                    ticks: {
                                        precision: 0,
                                        color: '#6b7280',
                                        font: { size: 12, weight: '500' }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    console.error('Canvas element with ID "supportProgressChart" not found');
                }
            @else
            console.log('Support progress data is empty or not set');
        @endif

        // Responsive chart resizing
        window.addEventListener('resize', function () {
            if (agendaChart) agendaChart.resize();
            if (statusPieChart) statusPieChart.resize();
            if (trendChart) trendChart.resize();
            @if($bulan && count($weekly_data) > 0)
                if (weeklyChart) weeklyChart.resize();
            @endif
            @if(count($top_issues) > 0)
                if (topIssuesChart) topIssuesChart.resize();
            @endif
            @if(count($priority_counts) > 0 && array_sum($priority_counts) > 0)
                if (priorityChart) priorityChart.resize();
            @endif
            @if(count($support_status_distribution) > 0)
                if (supportStatusChart) supportStatusChart.resize();
            @endif
            @if(isset($support_progress_data) && count($support_progress_data) > 0)
                if (supportProgressChart) supportProgressChart.resize();
            @endif
            });

        // Chart animation on scroll (optional enhancement)
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const chartObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const chartId = entry.target.id;
                    const chart = Chart.getChart(chartId);
                    if (chart) {
                        chart.update('active');
                    }
                }
            });
        }, observerOptions);

        // Observe all chart canvases
        document.querySelectorAll('.chart-wrapper canvas').forEach(canvas => {
            chartObserver.observe(canvas);
        });
    </script>
@endsection