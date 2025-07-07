@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="main-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-chart-pie"></i>
            GIAT DASHBOARD
        </h1>
        <p class="dashboard-subtitle">Visual Analytics untuk Support Needed & Escalation Management</p>

        <div class="quick-stats">
            <div class="quick-stat">
                <div class="quick-stat-number">1,247</div>
                <div class="quick-stat-label">Total Cases</div>
            </div>
            <div class="quick-stat">
                <div class="quick-stat-number">882</div>
                <div class="quick-stat-label">Selesai</div>
            </div>
            <div class="quick-stat">
                <div class="quick-stat-number">71%</div>
                <div class="quick-stat-label">Tingkat Keberhasilan</div>
            </div>
            <div class="quick-stat">
                <div class="quick-stat-number">237</div>
                <div class="quick-stat-label">Sedang Proses</div>
            </div>
        </div>
    </div>
</div>
@endsection