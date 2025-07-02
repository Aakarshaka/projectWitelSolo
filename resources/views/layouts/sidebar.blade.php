<div class="sidebar">
    <div class="sidebar-header">
        <h2 class="logo">BiSA<span class="logo-sub">g</span></h2>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <a href="{{ url('/dashboard') }}" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <span class="nav-text">Dashboard</span>
                <span class="nav-subtext">Giat</span>
            </a>
        </div>

        <div class="nav-section">
            <a href="{{ url('/warroom') }}" class="nav-item {{ Request::is('warroom') ? 'active' : '' }}">
                <span class="nav-text">WAR ROOM</span>
                <span class="nav-subtext">Activity</span>
            </a>
        </div>

        <div class="nav-section-header">
            <span class="section-title">Support Needed</span>
        </div>

        <div class="nav-section">
            <a href="{{ url('/summary') }}" class="nav-item {{ Request::is('summary') ? 'active' : '' }}">
                <span class="nav-text">Summary</span>
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-item">SN UNIT</div>
        </div>
        <div class="nav-section">
            <div class="nav-item">SN TELDA</div>
        </div>
        <div class="nav-section">
            <div class="nav-item">SN AM</div>
        </div>

        <div class="nav-section-header">
            <span class="section-title">ESKALASI</span>
        </div>

        <div class="nav-section">
            <div class="nav-item">to TREG</div>
        </div>
        <div class="nav-section">
            <div class="nav-item">to TIF_TA</div>
        </div>
        <div class="nav-section">
            <div class="nav-item">to TSEL</div>
        </div>
        <div class="nav-section">
            <div class="nav-item">to GSD</div>
        </div>
    </nav>
</div>
