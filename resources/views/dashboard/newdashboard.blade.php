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
                        <span class="stat-number-wr">{{ $total_users ?? 0 }}</span>
                        <span class="stat-label-wr">Total User</span>
                    </div>
                </div>
                <div class="stat-item-wr">
                    <div class="stat-icon-wr">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info-wr">
                        <span class="stat-number-wr">{{ $active_users ?? 0 }}</span>
                        <span class="stat-label-wr">User Aktif</span>
                    </div>
                </div>
                <div class="stat-item-wr">
                    <div class="stat-icon-wr">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="stat-info-wr">
                        <span class="stat-number-wr">{{ $total_activity_logs ?? 0 }}</span>
                        <span class="stat-label-wr">Total Perubahan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection