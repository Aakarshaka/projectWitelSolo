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