@extends('layouts.layout')

@section('title', 'DASHBOARD')

@section('content')
<!-- Header Section -->
<div class="dash-main-content">
    <div class="dash-header">
        <div class="dash-header-content">
            <h1 class="dash-title">DASHBOARD</h1>
            <div class="dash-header-info">
                <span class="dash-welcome-text">Welcome, {{ auth()->user()->name ?? 'User' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="dash-container">
        <div class="dash-grid">
            <!-- Hero Section -->
            <div class="dash-hero-section">
                <div class="dash-hero-content">
                    <div class="dash-hero-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="dash-hero-text">
                        <h2>Meta Bright</h2>
                        <p>Access the BPP system through the main dashboard for data monitoring and analysis.</p>
                    </div>
                </div>
                <div class="dash-hero-action">
                    <a href="http://10.60.170.171/bpp-dashboard/" class="dash-primary-btn" target="_blank">
                        <span class="dash-btn-icon">
                            <i class="fas fa-external-link-alt"></i>
                        </span>
                        <span class="dash-btn-text">Open BPP Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="dash-stats-grid">
                <div class="dash-stat-item">
                    <div class="dash-stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="dash-stat-info">
                        <span class="dash-stat-number">{{ $total_users ?? 0 }}</span>
                        <span class="dash-stat-label">Overall User</span>
                    </div>
                </div>
                <div class="dash-stat-item">
                    <div class="dash-stat-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="dash-stat-info">
                        <span class="dash-stat-number">{{ $total_activity_logs ?? 0 }}</span>
                        <span class="dash-stat-label">Overall Change</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="dash-footer">Powered by <strong>GIAT CORE</strong></div>
</div>
@endsection