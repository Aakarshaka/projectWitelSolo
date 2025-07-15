@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
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

    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
@endsection