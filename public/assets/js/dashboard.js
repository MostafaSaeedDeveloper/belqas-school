/**
 * BELQAS SCHOOL MANAGEMENT SYSTEM
 * Dashboard JavaScript - Version 1.0
 */

class Dashboard {
    constructor() {
        this.init();
        this.bindEvents();
        this.initCharts();
        this.initCalendar();
        this.loadDashboardData();
    }

    init() {
        // Remove loading screen
        this.hideLoadingScreen();
        
        // Initialize tooltips
        this.initTooltips();
        
        // Initialize search functionality
        this.initSearch();
        
        // Auto-refresh data every 5 minutes
        setInterval(() => {
            this.refreshStats();
        }, 300000);
    }

    hideLoadingScreen() {
        const loadingScreen = document.getElementById('loading-screen');
        if (loadingScreen) {
            setTimeout(() => {
                loadingScreen.classList.add('fade-out');
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 500);
            }, 1000);
        }
    }

    bindEvents() {
        // Stat cards hover effects
        this.initStatCards();
        
        // Action buttons
        this.initActionButtons();
        
        // Notification handling
        this.initNotifications();
        
        // Profile dropdown
        this.initProfileDropdown();
    }

    initTooltips() {
        const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
        tooltipTriggers.forEach(trigger => {
            trigger.addEventListener('mouseenter', this.showTooltip.bind(this));
            trigger.addEventListener('mouseleave', this.hideTooltip.bind(this));
        });
    }

    showTooltip(event) {
        const element = event.target;
        const tooltipText = element.getAttribute('data-tooltip');
        
        if (!tooltipText) return;
        
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip fade-in';
        tooltip.textContent = tooltipText;
        tooltip.style.cssText = `
            position: absolute;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            pointer-events: none;
            z-index: 1000;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();
        
        tooltip.style.left = `${rect.left + (rect.width / 2) - (tooltipRect.width / 2)}px`;
        tooltip.style.top = `${rect.top - tooltipRect.height - 8}px`;
        
        element.tooltipElement = tooltip;
    }

    hideTooltip(event) {
        const element = event.target;
        if (element.tooltipElement) {
            element.tooltipElement.remove();
            element.tooltipElement = null;
        }
    }

    initSearch() {
        const searchInput = document.querySelector('.search-input');
        if (!searchInput) return;

        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.performSearch(e.target.value);
            }, 300);
        });

        // Search suggestions
        searchInput.addEventListener('focus', () => {
            this.showSearchSuggestions();
        });

        searchInput.addEventListener('blur', () => {
            setTimeout(() => {
                this.hideSearchSuggestions();
            }, 200);
        });
    }

    performSearch(query) {
        if (query.length < 2) {
            this.hideSearchSuggestions();
            return;
        }

        // Show loading state
        this.showSearchLoading();

        // Simulate API call
        