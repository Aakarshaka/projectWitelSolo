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

        <li class="active">
            <a href="#" data-tooltip="Dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                    <rect width="7" height="9" x="3" y="3" rx="1" />
                    <rect width="7" height="5" x="14" y="3" rx="1" />
                    <rect width="7" height="9" x="14" y="12" rx="1" />
                    <rect width="7" height="5" x="3" y="16" rx="1" />
                </svg>
                <div class="tooltip right">Dashboard</div>
            </a>
        </li>

        <li>
            <a href="#" data-tooltip="War Room">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-presentation-icon lucide-presentation">
                    <path d="M2 3h20" />
                    <path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3" />
                    <path d="m7 21 5-5 5 5" />
                </svg>
                <div class="tooltip right">Warroom Activity </div>
            </a>
        </li>

        <li>
            <a href="#" data-tooltip="Summary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 16v5" />
                    <path d="M16 14v7" />
                    <path d="M20 10v11" />
                    <path d="m22 3-8.646 8.646a.5.5 0 0 1-.708 0L9.354 8.354a.5.5 0 0 0-.707 0L2 15" />
                    <path d="M4 18v3" />
                    <path d="M8 14v7" />
                </svg>
                <div class="tooltip right">Summary</div>
            </a>
        </li>

        <li>
            <a href="#" data-tooltip="Support Needed">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m11 17 2 2a1 1 0 1 0 3-3" />
                    <path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4" />
                    <path d="m21 3 1 11h-2" />
                    <path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3" />
                    <path d="M3 4h8" />
                </svg>
                <div class="tooltip right">Support Needed</div>
            </a>
        </li>

        <li style="margin-top: auto;">
            <a href="#" data-tooltip="Power Off">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 7v4" />
                    <path d="M7.998 9.003a5 5 0 1 0 8-.005" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
                <div class="tooltip right">Logout</div>
            </a>
        </li>
    </ul>
</nav>

<script>
    // Optional: Dynamic tooltip positioning based on viewport
    function adjustTooltipPosition() {
        const tooltips = document.querySelectorAll('.tooltip');

        tooltips.forEach(tooltip => {
            const rect = tooltip.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;

            // Reset classes
            tooltip.classList.remove('left', 'right', 'top', 'bottom');

            // On mobile, always show tooltip above
            if (window.innerWidth <= 800) {
                // Mobile tooltip positioning is handled by CSS
                return;
            }

            // Desktop positioning logic
            if (rect.right > viewportWidth - 10) {
                tooltip.classList.add('left');
            } else {
                tooltip.classList.add('right');
            }
        });
    }

    // Mobile touch support for tooltips
    function addMobileTooltipSupport() {
        if ('ontouchstart' in window) {
            const sidebarLinks = document.querySelectorAll('#sidebar a, #sidebar .dropdown-btn');

            sidebarLinks.forEach(link => {
                let touchTimer;

                link.addEventListener('touchstart', function(e) {
                    // Show tooltip on long press
                    touchTimer = setTimeout(() => {
                        const tooltip = this.querySelector('.tooltip');
                        if (tooltip) {
                            tooltip.style.opacity = '1';
                            tooltip.style.visibility = 'visible';

                            // Hide tooltip after 2 seconds
                            setTimeout(() => {
                                tooltip.style.opacity = '0';
                                tooltip.style.visibility = 'hidden';
                            }, 2000);
                        }
                    }, 300);
                });

                link.addEventListener('touchend', function(e) {
                    clearTimeout(touchTimer);
                });

                link.addEventListener('touchmove', function(e) {
                    clearTimeout(touchTimer);
                });
            });
        }
    }

    // Adjust tooltip positions on window resize
    window.addEventListener('resize', adjustTooltipPosition);

    // Initial adjustment
    document.addEventListener('DOMContentLoaded', function() {
        adjustTooltipPosition();
        addMobileTooltipSupport();
    });
</script>