@extends('layouts.layout')

@section('title', 'DASHBOARD')

@section('content')
<!-- Header Section -->
<div class="main-content-dash">
    <div class="dash-header-wr">
        <div class="header-content-wr">
            <h1 class="dash-title-wr">DASHBOARD</h1>
            <div class="header-info-wr">
                <span class="welcome-text-wr">Selamat datang, {{ auth()->user()->name ?? 'User' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="dash-container-wr">
        <div class="dashboard-grid-wr">
            <!-- Hero Section -->
            <div class="hero-section-wr">
                <div class="hero-content-wr">
                    <div class="hero-icon-wr">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="hero-text-wr">
                        <h2>Giat Core System</h2>
                        <p>Akses sistem BPP melalui dashboard utama untuk monitoring dan analisis data</p>
                    </div>
                </div>
                <div class="hero-action-wr">
                    <a href="http://10.60.170.171/bpp-dashboard/" class="primary-btn-wr" target="_blank">
                        <span class="btn-icon-wr">
                            <i class="fas fa-external-link-alt"></i>
                        </span>
                        <span class="btn-text-wr">Buka BPP Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid-wr">
                <div class="stat-item-wr">
                    <div class="stat-icon-wr">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info-wr">
                        <span class="stat-number-wr">150+</span>
                        <span class="stat-label-wr">Active Users</span>
                    </div>
                </div>
                <div class="stat-item-wr">
                    <div class="stat-icon-wr">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stat-info-wr">
                        <span class="stat-number-wr">2.5K</span>
                        <span class="stat-label-wr">Data Records</span>
                    </div>
                </div>
                <div class="stat-item-wr">
                    <div class="stat-icon-wr">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="stat-info-wr">
                        <span class="stat-number-wr">98%</span>
                        <span class="stat-label-wr">System Uptime</span>
                    </div>
                </div>
            </div>

            <!-- Quick Access -->
            <div class="quick-access-wr">
                <h3 class="section-title-wr">Quick Access</h3>
                <div class="access-grid-wr">
                    <div class="access-item-wr">
                        <div class="access-icon-wr">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <span>System Monitor</span>
                    </div>
                    <div class="access-item-wr">
                        <div class="access-icon-wr">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span>Reports</span>
                    </div>
                    <div class="access-item-wr">
                        <div class="access-icon-wr">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span>Settings</span>
                    </div>
                    <div class="access-item-wr">
                        <div class="access-icon-wr">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span>Notifications</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection