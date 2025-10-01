/**
 * SIDEBAR FUNCTIONALITY
 * Belqas School Management System
 */

class SidebarManager {
    constructor() {
        this.sidebar = document.getElementById('sidebar');
        this.sidebarToggle = document.getElementById('sidebarToggle');
        this.sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
        this.sidebarOverlay = document.getElementById('sidebarOverlay');
        this.mainContent = document.querySelector('.main-content');
        
        this.isCollapsed = BelqasApp.storage.get('sidebar_collapsed', false);
        this.isMobile = window.innerWidth < 992;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupInitialState();
        this.setupSubmenuHandlers();
        this.setupActiveMenuItem();
        this.setupResponsiveHandlers();
    }

    setupEventListeners() {
        // Toggle button (desktop + mobile trigger)
        [this.sidebarToggle, this.sidebarToggleMobile].forEach(button => {
            if (button) {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggle();
                });
            }
        });

        // Overlay click (mobile)
        if (this.sidebarOverlay) {
            this.sidebarOverlay.addEventListener('click', () => {
                this.close();
            });
        }

        // Window resize
        window.addEventListener('resize', this.debounce(() => {
            this.handleResize();
        }, 250));

        // ESC key to close (mobile)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isMobile && this.isOpen()) {
                this.close();
            }
        });
    }

    setupInitialState() {
        if (this.isMobile) {
            this.sidebar?.classList.remove('sidebar-collapsed');
            this.close();
        } else {
            if (this.isCollapsed) {
                this.collapse();
            } else {
                this.expand();
            }
        }
    }

    setupSubmenuHandlers() {
        const submenuItems = document.querySelectorAll('.has-submenu > .nav-link');
        
        submenuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleSubmenu(item.closest('.has-submenu'));
            });
        });
    }

    setupActiveMenuItem() {
        // Set active menu item based on current URL
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.nav-link');
        
        menuLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && (currentPath === href || currentPath.startsWith(href + '/'))) {
                link.classList.add('active');
                
                // Open parent submenu if this is a submenu item
                const parentSubmenu = link.closest('.has-submenu');
                if (parentSubmenu) {
                    parentSubmenu.classList.add('menu-open');
                }
            }
        });
    }

    setupResponsiveHandlers() {
        // Handle mobile menu items
        const navLinks = document.querySelectorAll('.nav-link:not(.has-submenu .nav-link)');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (this.isMobile) {
                    setTimeout(() => {
                        this.close();
                    }, 150);
                }
            });
        });
    }

    toggle() {
        if (this.isMobile) {
            this.isOpen() ? this.close() : this.open();
        } else {
            this.isCollapsed ? this.expand() : this.collapse();
        }
    }

    collapse() {
        if (this.isMobile) return;
        
        this.sidebar?.classList.add('sidebar-collapsed');
        document.body.classList.add('sidebar-collapsed');
        document.body.classList.remove('sidebar-expanded');
        
        this.isCollapsed = true;
        BelqasApp.storage.set('sidebar_collapsed', true);
        
        // Close all submenus when collapsing
        this.closeAllSubmenus();
        
        this.updateTooltips();
    }

    expand() {
        if (this.isMobile) return;
        
        this.sidebar?.classList.remove('sidebar-collapsed');
        document.body.classList.add('sidebar-expanded');
        document.body.classList.remove('sidebar-collapsed');
        
        this.isCollapsed = false;
        BelqasApp.storage.set('sidebar_collapsed', false);
        
        this.removeTooltips();
    }

    open() {
        if (!this.isMobile) return;
        
        this.sidebar?.classList.add('mobile-open');
        this.sidebarOverlay?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    close() {
        if (!this.isMobile) return;
        
        this.sidebar?.classList.remove('mobile-open');
        this.sidebarOverlay?.classList.remove('active');
        document.body.style.overflow = '';
    }

    isOpen() {
        return this.sidebar?.classList.contains('mobile-open');
    }

    toggleSubmenu(menuItem) {
        if (this.isCollapsed && !this.isMobile) return;
        
        const isOpen = menuItem.classList.contains('menu-open');
        
        // Close other submenus (accordion behavior)
        const allSubmenus = document.querySelectorAll('.has-submenu.menu-open');
        allSubmenus.forEach(submenu => {
            if (submenu !== menuItem) {
                submenu.classList.remove('menu-open');
            }
        });
        
        // Toggle current submenu
        if (isOpen) {
            menuItem.classList.remove('menu-open');
        } else {
            menuItem.classList.add('menu-open');
        }
    }

    closeAllSubmenus() {
        const openSubmenus = document.querySelectorAll('.has-submenu.menu-open');
        openSubmenus.forEach(submenu => {
            submenu.classList.remove('menu-open');
        });
    }

    updateTooltips() {
        if (!this.isCollapsed) return;
        
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            const text = link.querySelector('.nav-text')?.textContent.trim();
            if (text && !link.closest('.nav-submenu')) {
                link.setAttribute('data-tooltip', text);
                link.addEventListener('mouseenter', this.showTooltip);
                link.addEventListener('mouseleave', this.hideTooltip);
            }
        });
    }

    removeTooltips() {
        const navLinks = document.querySelectorAll('.sidebar .nav-link[data-tooltip]');
        navLinks.forEach(link => {
            link.removeAttribute('data-tooltip');
            link.removeEventListener('mouseenter', this.showTooltip);
            link.removeEventListener('mouseleave', this.hideTooltip);
        });
    }

    showTooltip(event) {
        const element = event.currentTarget;
        const tooltipText = element.getAttribute('data-tooltip');
        
        if (!tooltipText) return;
        
        const tooltip = document.createElement('div');
        tooltip.className = 'sidebar-tooltip';
        tooltip.textContent = tooltipText;
        tooltip.style.cssText = `
            position: fixed;
            left: 90px;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            pointer-events: none;
            z-index: 10000;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.2s ease;
        `;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.top = `${rect.top + (rect.height / 2) - (tooltip.offsetHeight / 2)}px`;
        
        // Animate in
        requestAnimationFrame(() => {
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateX(0)';
        });
        
        element._tooltip = tooltip;
    }

    hideTooltip(event) {
        const element = event.currentTarget;
        if (element._tooltip) {
            element._tooltip.style.opacity = '0';
            element._tooltip.style.transform = 'translateX(-10px)';
            setTimeout(() => {
                if (element._tooltip && element._tooltip.parentNode) {
                    element._tooltip.parentNode.removeChild(element._tooltip);
                }
                element._tooltip = null;
            }, 200);
        }
    }

    handleResize() {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth < 992;
        
        if (wasMobile !== this.isMobile) {
            if (this.isMobile) {
                // Switched to mobile
                this.sidebar?.classList.remove('sidebar-collapsed');
                document.body.classList.remove('sidebar-collapsed', 'sidebar-expanded');
                this.close();
                this.removeTooltips();
            } else {
                // Switched to desktop
                this.sidebar?.classList.remove('mobile-open');
                this.sidebarOverlay?.classList.remove('active');
                document.body.style.overflow = '';
                
                if (this.isCollapsed) {
                    this.collapse();
                } else {
                    this.expand();
                }
            }
        }
    }

    // Utility function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize Sidebar when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.sidebarManager = new SidebarManager();
});

// CSS for tooltips and animations
const sidebarStyles = `
<style>
.sidebar-tooltip {
    font-family: 'Cairo', sans-serif;
}

.sidebar.sidebar-collapsed .nav-link:hover {
    background: var(--gradient-primary);
    color: white;
    border-radius: var(--border-radius-md);
}

.has-submenu .nav-submenu {
    transition: max-height 0.3s ease;
}

.loading {
    pointer-events: none;
    opacity: 0.6;
}

@media (max-width: 991.98px) {
    .sidebar {
        transform: translateX(100%);
    }
    
    .sidebar.mobile-open {
        transform: translateX(0);
    }
    
    .sidebar-overlay {
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', sidebarStyles);