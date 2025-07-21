@extends('layouts.layout')

@section('title', 'DASHBOARD')

@section('content')
<!-- Header Section -->
<div class="main-content-dash">
    <div class="dash-header-d">
        <div class="header-content-d">
            <h1 class="dash-title-d">DASHBOARD</h1>
            <div class="header-info-d">
                <span class="welcome-text-d">Selamat datang, {{ auth()->user()->name ?? 'User' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="dash-container-d">
        <div class="dashboard-grid-d">
            <!-- Hero Section -->
            <div class="hero-section-d">
                <div class="hero-content-d">
                    <div class="hero-icon-d">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="hero-text-d">
                        <h2>Giat Core System</h2>
                        <p>Akses sistem BPP melalui dashboard utama untuk monitoring dan analisis data</p>
                    </div>
                </div>
                <div class="hero-action-d">
                    <a href="http://10.60.170.171/bpp-dashboard/" class="primary-btn-d" target="_blank">
                        <span class="btn-icon-d">
                            <i class="fas fa-external-link-alt"></i>
                        </span>
                        <span class="btn-text-d">Buka BPP Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid-d">
                <div class="stat-item-d">
                    <div class="stat-icon-d">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info-d">
                        <span class="stat-number-d">{{ $total_users ?? 0 }}</span>
                        <span class="stat-label-d">Total User</span>
                    </div>
                </div>
                <div class="stat-item-d">
                    <div class="stat-icon-d">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info-d">
                        <span class="stat-number-d">{{ $active_users ?? 0 }}</span>
                        <span class="stat-label-d">User Aktif</span>
                    </div>
                </div>
                <div class="stat-item-d">
                    <div class="stat-icon-d">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="stat-info-d">
                        <span class="stat-number-d">{{ $total_activity_logs ?? 0 }}</span>
                        <span class="stat-label-d">Total Perubahan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection