<div class="sidebar">
    <div class="sidebar-header">
        <h2 class="logo">GIAT<span class="logo-sub">CORE</span></h2>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <a href="{{ route('dashboard') }}" class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-text">Dashboard</span>
                <span class="nav-subtext">Giat</span>
            </a>
        </div>

        <div class="nav-section">
            <a href="{{ route('warroom.index') }}" class="nav-item {{ Request::routeIs('warroom.index') ? 'active' : '' }}">
                <span class="nav-text">WAR ROOM</span>
                <span class="nav-subtext">Activity</span>
            </a>
        </div>

        <div class="nav-section-header">
            <span class="section-title">Support Needed</span>
        </div>

        <div class="nav-section">
            <a href="{{ route('summary.index') }}" class="nav-item {{ Request::routeIs('summary.index') ? 'active' : '' }}">
                <span class="nav-text">Summary</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ route('snunit.index') }}" class="nav-item {{ Request::routeIs('snunit.index') ? 'active' : '' }}">
                <span class="nav-text">SN UNIT</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ route('sntelda.index') }}" class="nav-item {{ Request::routeIs('sntelda.index') ? 'active' : '' }}">
                <span class="nav-text">SN TELDA</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ route('snam.index') }}" class="nav-item {{ Request::routeIs('snam.index') ? 'active' : '' }}">
                <span class="nav-text">SN AM</span>
            </a>
        </div>

        <div class="nav-section-header">
            <span class="section-title">ESKALASI</span>
        </div>

        <div class="nav-section">
            <a href="{{ route('treg.index') }}" class="nav-item {{ Request::routeIs('treg.index') ? 'active' : '' }}">
                <span class="nav-text">to TREG</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ route('tifta.index') }}" class="nav-item {{ Request::routeIs('tifta.index') ? 'active' : '' }}">
                <span class="nav-text">to TIF_TA</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ route('tsel.index') }}" class="nav-item {{ Request::routeIs('tsel.index') ? 'active' : '' }}">
                <span class="nav-text">to TSEL</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ route('gsd.index') }}" class="nav-item {{ Request::routeIs('gsd.index') ? 'active' : '' }}">
                <span class="nav-text">to GSD</span>
            </a>
        </div>

        <div class="nav-section">
            <a href="{{ route('witel.index') }}" class="nav-item {{ Request::routeIs('witel.index') ? 'active' : '' }}">
                <span class="nav-text">to UNIT WITEL</span>
            </a>
        </div>

        <div class="nav-section-header"></div>

        <div class="nav-section">
            <a href="{{ route('login') }}" class="nav-item">
                <span class="nav-text">Logout</span>
            </a>
        </div>
    </nav>
</div>
