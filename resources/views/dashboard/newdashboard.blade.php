@extends('layouts.layout')

@section('title', 'DASHBOARD')

@section('content')
<!-- Header Section -->
<div class="dash-main-content">
    <div class="dash-header">
        <div class="dash-header-content">
            <h1 class="dash-title">DASHBOARD</h1>
            <div class="dash-header-info">
                <div class="dash-user-info">
                    <span class="dash-welcome-text">Welcome, {{ auth()->user()->name ?? 'User' }} {{ auth()->user()->role ?? 'User' }}</span>
                    <div class="dash-datetime-info">
                        <span id="current-datetime"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="dash-container">
        <div class="dash-grid">
            <!-- Hero Section -->
            <div class="dash-hero-section">
                <div class="dash-hero-content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-component-icon lucide-component">
                        <path d="M15.536 11.293a1 1 0 0 0 0 1.414l2.376 2.377a1 1 0 0 0 1.414 0l2.377-2.377a1 1 0 0 0 0-1.414l-2.377-2.377a1 1 0 0 0-1.414 0z" />
                        <path d="M2.297 11.293a1 1 0 0 0 0 1.414l2.377 2.377a1 1 0 0 0 1.414 0l2.377-2.377a1 1 0 0 0 0-1.414L6.088 8.916a1 1 0 0 0-1.414 0z" />
                        <path d="M8.916 17.912a1 1 0 0 0 0 1.415l2.377 2.376a1 1 0 0 0 1.414 0l2.377-2.376a1 1 0 0 0 0-1.415l-2.377-2.376a1 1 0 0 0-1.414 0z" />
                        <path d="M8.916 4.674a1 1 0 0 0 0 1.414l2.377 2.376a1 1 0 0 0 1.414 0l2.377-2.376a1 1 0 0 0 0-1.414l-2.377-2.377a1 1 0 0 0-1.414 0z" />
                    </svg>
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

            <!-- User Statistics -->
            <div class="dash-user-stats">
                <div class="dash-stat-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-check-icon lucide-user-round-check">
                        <path d="M2 21a8 8 0 0 1 13.292-6" />
                        <circle cx="10" cy="8" r="5" />
                        <path d="m16 19 2 2 4-4" />
                    </svg>
                    <div class="dash-stat-info">
                        <span class="dash-stat-number">{{ $total_active }}</span>
                        <span class="dash-stat-label">Active Users</span>
                    </div>
                </div>
                <div class="dash-stat-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-x-icon lucide-user-round-x">
                        <path d="M2 21a8 8 0 0 1 11.873-7" />
                        <circle cx="10" cy="8" r="5" />
                        <path d="m17 17 5 5" />
                        <path d="m22 17-5 5" />
                    </svg>
                    <div class="dash-stat-info">
                        <span class="dash-stat-number">{{ $total_inactive }}</span>
                        <span class="dash-stat-label">Inactive Users</span>
                    </div>
                </div>
                <div class="dash-stat-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round">
                        <path d="M18 21a8 8 0 0 0-16 0" />
                        <circle cx="10" cy="8" r="5" />
                        <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3" />
                    </svg>
                    <div class="dash-stat-info">
                        <span class="dash-stat-number">{{ $total_users }}</span>
                        <span class="dash-stat-label">Total Users</span>
                    </div>
                </div>
            </div>

            <!-- Users Section -->
            <div class="dash-users-section">
                <!-- Active Users -->
                <div class="dash-user-group">
                    <div class="dash-user-group-header">
                        <h3><i class="fas fa-circle active-indicator"></i> Active Users ({{ $total_active }})</h3>
                    </div>
                    <div class="dash-user-list" id="active-users">
                        @forelse($active_users as $user)
                        <div class="dash-user-item active">
                            <div class="dash-user-avatar">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="dash-user-info">
                                <div class="dash-user-name">{{ $user->name }}</div>
                                <div class="dash-user-email">{{ $user->email }}</div>
                            </div>
                            <div class="dash-user-meta">
                                <span class="dash-user-role {{ strtolower($user->role) }}">{{ ucfirst($user->role) }}</span>
                                <span class="dash-user-status active">Active</span>
                            </div>
                        </div>
                        @empty
                        <div class="dash-empty-state">
                            <i class="fas fa-users"></i>
                            <p>No active users found</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Inactive Users -->
                <div class="dash-user-group">
                    <div class="dash-user-group-header">
                        <h3><i class="fas fa-circle inactive-indicator"></i> Inactive Users ({{ $total_inactive }})</h3>
                    </div>
                    <div class="dash-user-list" id="inactive-users">
                        @forelse($inactive_users as $user)
                        <div class="dash-user-item inactive">
                            <div class="dash-user-avatar inactive">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="dash-user-info">
                                <div class="dash-user-name">{{ $user->name }}</div>
                                <div class="dash-user-email">{{ $user->email }}</div>
                            </div>
                            <div class="dash-user-meta">
                                <span class="dash-user-role {{ strtolower($user->role) }}">{{ ucfirst($user->role) }}</span>
                                <span class="dash-user-status inactive">Inactive</span>
                            </div>
                        </div>
                        @empty
                        <div class="dash-empty-state">
                            <i class="fas fa-user-slash"></i>
                            <p>No inactive users found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dash-footer">Powered by <strong>GIAT CORE</strong></div>
</div>

<script>
function updateDateTime() {
    const now = new Date();
    
    // Format waktu Indonesia
    const options = {
        timeZone: 'Asia/Jakarta',
        weekday: 'long',
        year: 'numeric',
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    };
    
    const formatter = new Intl.DateTimeFormat('id-ID', options);
    const formattedDate = formatter.format(now);
    
    document.getElementById('current-datetime').textContent = formattedDate;
}

// Update waktu setiap detik
updateDateTime();
setInterval(updateDateTime, 1000);
</script>
@endsection