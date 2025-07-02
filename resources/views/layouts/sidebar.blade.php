<div class="sidebar">
    <div class="sidebar-header">
        <h2 class="logo">GIAT<span class="logo-sub">CORE</span></h2>
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
            <a href="{{ url('supportNeeded/summary') }}" class="nav-item {{ Request::is('supportNeeded/summary') ? 'active' : '' }}">
                <span class="nav-text">Summary</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ url('supportNeeded/snunit') }}" class="nav-item {{ Request::is('supportNeeded/snunit') ? 'active' : '' }}">
                <span class="nav-text">SN UNIT</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ url('supportNeeded/sntelda') }}" class="nav-item {{ Request::is('supportNeeded/sntelda') ? 'active' : '' }}">
                <span class="nav-text">SN TELDA</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ url('supportNeeded/snam') }}" class="nav-item {{ Request::is('supportNeeded/snam') ? 'active' : '' }}">
                <span class="nav-text">SN AM</span>
            </a>
        </div>

        <div class="nav-section-header">
            <span class="section-title">ESKALASI</span>
        </div>

        <div class="nav-section">
            <a href="{{ url('eskalasi/treg') }}" class="nav-item {{ Request::is('eskalasi/treg') ? 'active' : '' }}">
                <span class="nav-text">to TREG</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ url('eskalasi/tifta') }}" class="nav-item {{ Request::is('eskalasi/tifta') ? 'active' : '' }}">
                <span class="nav-text">to TIF_TA</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ url('eskalasi/tsel') }}" class="nav-item {{ Request::is('eskalasi/tsel') ? 'active' : '' }}">
                <span class="nav-text">to TSEL</span>
            </a>
        </div>
        <div class="nav-section">
            <a href="{{ url('eskalasi/gsd') }}" class="nav-item {{ Request::is('eskalasi/gsd') ? 'active' : '' }}">
                <span class="nav-text">to GSD</span>
            </a>
        </div>
    </nav>
</div>
