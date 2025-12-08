document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const body = document.body;

    // Get all possible main content containers
    const mainContainers = [
        document.querySelector('.main-content'),
        document.querySelector('.main-wrapper'),
        document.querySelector('.page-content'),
        document.querySelector('.page-wrapper')
    ].filter(el => el !== null);

    // Create overlay
    let overlay = document.querySelector('.sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
    }

    if (!toggleBtn || !sidebar) {
        console.error('Required elements not found');
        return;
    }

    // Restore state
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true') {
        sidebar.classList.add('collapsed');
        body.classList.add('sidebar-collapsed');
        toggleBtn.classList.add('active');
    }

    // Add tooltips
    document.querySelectorAll('.sidebar-menu a').forEach(link => {
        const span = link.querySelector('span');
        if (span) {
            link.setAttribute('data-tooltip', span.textContent.trim());
        }
    });

    // Toggle functionality
    toggleBtn.addEventListener('click', function() {
        const isMobile = window.innerWidth <= 1024;

        if (isMobile) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        } else {
            // Desktop toggle
            this.classList.toggle('active');
            sidebar.classList.toggle('collapsed');
            body.classList.toggle('sidebar-collapsed');

            // Save state
            localStorage.setItem('sidebarCollapsed',
                sidebar.classList.contains('collapsed'));
        }
    });

    // Close on overlay click
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        body.style.overflow = '';
    });

    // Close on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            body.style.overflow = '';
        }
    });

    // Submenu toggles
    document.querySelectorAll('.submenu > a').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!sidebar.classList.contains('collapsed')) {
                e.preventDefault();
                this.closest('.submenu').classList.toggle('submenu-open');
            }
        });
    });

    // Window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                body.style.overflow = '';
            }
        }, 250);
    });

    // User dropdown
    const userBtn = document.querySelector('.user-btn');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    if (userBtn && dropdownMenu) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!userBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
});
