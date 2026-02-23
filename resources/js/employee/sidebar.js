{{-- resources/js/employee/sidebar.js --}}
class EmployeeSidebar {
    constructor() {
        this.sidebar = document.getElementById('sidebar');
        this.toggleBtn = document.getElementById('sidebarToggle');
        this.mobileToggle = document.getElementById('mobileToggle');
        this.overlay = document.getElementById('sidebarOverlay');
        this.submenuParents = document.querySelectorAll('.nav-parent');

        this.init();
    }

    init() {
        // Load saved state
        this.loadState();

        // Event listeners
        this.toggleBtn?.addEventListener('click', () => this.toggleSidebar());
        this.mobileToggle?.addEventListener('click', () => this.openMobileSidebar());
        this.overlay?.addEventListener('click', () => this.closeMobileSidebar());

        // Submenu toggle
        this.submenuParents.forEach(parent => {
            const toggleBtn = parent.querySelector('.nav-link[data-bs-toggle="collapse"]');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', (e) => {
                    if (window.innerWidth > 768 && !this.sidebar.classList.contains('collapsed')) {
                        e.preventDefault();
                        const target = document.querySelector(toggleBtn.getAttribute('data-bs-target'));
                        const isOpen = parent.classList.contains('open');

                        // Close all other submenus
                        this.submenuParents.forEach(p => {
                            if (p !== parent) {
                                p.classList.remove('open');
                                const otherTarget = p.querySelector('.submenu');
                                if (otherTarget) {
                                    otherTarget.classList.remove('show');
                                }
                            }
                        });

                        // Toggle current
                        parent.classList.toggle('open');
                        if (target) {
                            target.classList.toggle('show');
                        }

                        // Save submenu state
                        this.saveSubmenuState(parent.id, !isOpen);
                    }
                });
            }
        });

        // Close sidebar on mobile when clicking a link
        document.querySelectorAll('.nav-item, .submenu-item').forEach(item => {
            if (!item.classList.contains('logout-btn')) {
                item.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        this.closeMobileSidebar();
                    }
                });
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.sidebar.classList.contains('mobile-open')) {
                this.closeMobileSidebar();
            }
        });

        // Window resize
        window.addEventListener('resize', () => this.handleResize());
    }

    toggleSidebar() {
        this.sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebar_collapsed', this.sidebar.classList.contains('collapsed'));

        // Close any open submenus when collapsing
        if (this.sidebar.classList.contains('collapsed')) {
            this.submenuParents.forEach(parent => {
                parent.classList.remove('open');
                const target = parent.querySelector('.submenu');
                if (target) {
                    target.classList.remove('show');
                }
            });
        }
    }

    openMobileSidebar() {
        this.sidebar.classList.add('mobile-open');
        this.overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeMobileSidebar() {
        this.sidebar.classList.remove('mobile-open');
        this.overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    loadState() {
        // Load collapsed state
        const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
        if (isCollapsed) {
            this.sidebar.classList.add('collapsed');
        }

        // Load submenu states
        this.submenuParents.forEach(parent => {
            const isOpen = localStorage.getItem(`submenu_${parent.id}`) === 'true';
            if (isOpen) {
                parent.classList.add('open');
                const target = parent.querySelector('.submenu');
                if (target) {
                    target.classList.add('show');
                }
            }
        });
    }

    saveSubmenuState(id, isOpen) {
        localStorage.setItem(`submenu_${id}`, isOpen);
    }

    handleResize() {
        if (window.innerWidth > 768) {
            this.closeMobileSidebar();
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new EmployeeSidebar();
});
