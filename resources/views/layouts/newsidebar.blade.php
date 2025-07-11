<nav id="sidebar">
    <ul>
        <li>
            <img src="{{ asset('images/giatlogo.png') }}" class="logo-img">
            <button onclick="toggleSidebar()" id="toggle-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-left-icon lucide-chevrons-left">
                    <path d="m11 17-5-5 5-5" />
                    <path d="m18 17-5-5 5-5" />
                </svg>
            </button>
        </li>

        <li class="{{ Request::is('dashboard.newdashboard') ? 'active' : '' }}">
            <a href="{{ url('dashboard/newdashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                    <rect width="7" height="9" x="3" y="3" rx="1" />
                    <rect width="7" height="5" x="14" y="3" rx="1" />
                    <rect width="7" height="9" x="14" y="12" rx="1" />
                    <rect width="7" height="5" x="3" y="16" rx="1" />
                </svg>
                <div class="tooltip">Dashboard</div>
            </a>
        </li>

        <li class="{{ Request::is('warroom.newwarroom') ? 'active' : '' }}">
            <a href="{{ url('warroom/newwarroom') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-presentation-icon lucide-presentation">
                    <path d="M2 3h20" />
                    <path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3" />
                    <path d="m7 21 5-5 5 5" />
                </svg>
                <div class="tooltip">War Room</div>
            </a>
        </li>

        <li class="{{ Request::is('summary.newsummary') ? 'active' : '' }}">
            <a href="{{ url('summary/newsummary') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 16v5" />
                    <path d="M16 14v7" />
                    <path d="M20 10v11" />
                    <path d="m22 3-8.646 8.646a.5.5 0 0 1-.708 0L9.354 8.354a.5.5 0 0 0-.707 0L2 15" />
                    <path d="M4 18v3" />
                    <path d="M8 14v7" />
                </svg>
                <div class="tooltip">Summary</div>
            </a>
        </li>

        <li class="{{ Request::is('supportneeded') ? 'active' : '' }}">
            <a href="{{ url('/supportneeded') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m11 17 2 2a1 1 0 1 0 3-3" />
                    <path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4" />
                    <path d="m21 3 1 11h-2" />
                    <path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3" />
                    <path d="M3 4h8" />
                </svg>
                <div class="tooltip">Support Needed</div>
            </a>
        </li>

        <li style="margin-top: auto;">
            <a href="{{ url('/') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 7v4" />
                    <path d="M7.998 9.003a5 5 0 1 0 8-.005" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
                <div class="tooltip">Logout</div>
            </a>
        </li>

    </ul>
</nav>

<script>
    // Simplified tooltip script - tidak perlu kompleks karena CSS sudah handle positioning
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile touch support untuk tooltip
        if ('ontouchstart' in window) {
            const sidebarLinks = document.querySelectorAll('#sidebar a');

            sidebarLinks.forEach(link => {
                let touchTimer;
                let tooltipTimer;

                link.addEventListener('touchstart', function(e) {
                    const tooltip = this.querySelector('.tooltip');
                    if (!tooltip) return;

                    clearTimeout(touchTimer);
                    clearTimeout(tooltipTimer);

                    touchTimer = setTimeout(() => {
                        tooltip.style.opacity = '1';
                        tooltip.style.visibility = 'visible';

                        tooltipTimer = setTimeout(() => {
                            tooltip.style.opacity = '0';
                            tooltip.style.visibility = 'hidden';
                        }, 2000);
                    }, 300);
                });

                link.addEventListener('touchend', function() {
                    clearTimeout(touchTimer);
                });

                link.addEventListener('touchmove', function() {
                    clearTimeout(touchTimer);
                });
            });
        }
    });

    function toggleSidebar() {
        // Placeholder function
        console.log('Toggle sidebar');
    }
</script>