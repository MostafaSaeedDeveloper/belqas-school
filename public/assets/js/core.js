/**
 * BELQAS SCHOOL MANAGEMENT SYSTEM
 * Core JavaScript Functions - Version 1.0
 */

// Global App Object
window.BelqasApp = {
    config: {
        baseUrl: window.location.origin,
        apiUrl: window.location.origin + '/api',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    },
    
    // Initialize App
    init() {
        this.setupCSRF();
        this.setupTooltips();
        this.setupModals();
        this.setupAlerts();
        this.setupAjax();
        this.hideLoadingScreen();
        
        console.log('🚀 Belqas School System Initialized');
    },

    // Setup CSRF Token for AJAX requests
    setupCSRF() {
        if (this.config.csrfToken) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken
                }
            });
        }
    },

    // Initialize Tooltips
    setupTooltips() {
        // Initialize Bootstrap tooltips if available
        if (typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
        
        // Custom tooltip system
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', this.showTooltip);
            element.addEventListener('mouseleave', this.hideTooltip);
        });
    },

    // Show Custom Tooltip
    showTooltip(event) {
        const element = event.target;
        const tooltipText = element.getAttribute('data-tooltip');
        
        if (!tooltipText) return;
        
        const tooltip = document.createElement('div');
        tooltip.className = 'custom-tooltip';
        tooltip.textContent = tooltipText;
        tooltip.style.cssText = `
            position: absolute;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            pointer-events: none;
            z-index: 10000;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.2s ease;
        `;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();
        
        tooltip.style.left = `${rect.left + (rect.width / 2) - (tooltipRect.width / 2)}px`;
        tooltip.style.top = `${rect.top - tooltipRect.height - 8}px`;
        
        // Animate in
        requestAnimationFrame(() => {
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateY(0)';
        });
        
        element._tooltip = tooltip;
    },

    // Hide Custom Tooltip
    hideTooltip(event) {
        const element = event.target;
        if (element._tooltip) {
            element._tooltip.style.opacity = '0';
            element._tooltip.style.transform = 'translateY(10px)';
            setTimeout(() => {
                if (element._tooltip && element._tooltip.parentNode) {
                    element._tooltip.parentNode.removeChild(element._tooltip);
                }
                element._tooltip = null;
            }, 200);
        }
    },

    // Setup Modals
    setupModals() {
        // Modal close handlers
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-backdrop') || 
                e.target.classList.contains('btn-modal-close')) {
                this.closeModal(e.target.closest('.modal'));
            }
        });

        // ESC key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal.show');
                if (openModal) {
                    this.closeModal(openModal);
                }
            }
        });
    },

    // Open Modal
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            // Animate in
            requestAnimationFrame(() => {
                modal.style.opacity = '1';
                const dialog = modal.querySelector('.modal-dialog');
                if (dialog) {
                    dialog.style.transform = 'translate(-50%, -50%) scale(1)';
                }
            });
        }
    },

    // Close Modal
    closeModal(modal) {
        if (!modal) return;
        
        modal.style.opacity = '0';
        const dialog = modal.querySelector('.modal-dialog');
        if (dialog) {
            dialog.style.transform = 'translate(-50%, -50%) scale(0.9)';
        }
        
        setTimeout(() => {
            modal.classList.remove('show');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    },

    // Setup Alert System
    setupAlerts() {
        // Auto-hide alerts
        document.querySelectorAll('.alert').forEach(alert => {
            if (alert.hasAttribute('data-auto-hide')) {
                const delay = parseInt(alert.getAttribute('data-auto-hide')) || 5000;
                setTimeout(() => {
                    this.hideAlert(alert);
                }, delay);
            }
        });

        // Alert close buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('alert-close') || 
                e.target.closest('.alert-close')) {
                const alert = e.target.closest('.alert');
                if (alert) {
                    this.hideAlert(alert);
                }
            }
        });
    },

    // Show Alert
    showAlert(message, type = 'info', duration = 5000) {
        const alertContainer = document.getElementById('alert-container') || this.createAlertContainer();
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible show`;
        alert.innerHTML = `
            <div class="alert-content">
                <i class="alert-icon fas fa-${this.getAlertIcon(type)}"></i>
                <span class="alert-message">${message}</span>
            </div>
            <button type="button" class="alert-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Animate in
        requestAnimationFrame(() => {
            alert.style.opacity = '1';
            alert.style.transform = 'translateX(0)';
        });
        
        // Auto hide
        if (duration > 0) {
            setTimeout(() => {
                this.hideAlert(alert);
            }, duration);
        }
        
        return alert;
    },

    // Hide Alert
    hideAlert(alert) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (alert.parentNode) {
                alert.parentNode.removeChild(alert);
            }
        }, 300);
    },

    // Create Alert Container
    createAlertContainer() {
        const container = document.createElement('div');
        container.id = 'alert-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 10000;
            pointer-events: none;
        `;
        document.body.appendChild(container);
        return container;
    },

    // Get Alert Icon
    getAlertIcon(type) {
        const icons = {
            'success': 'check-circle',
            'info': 'info-circle',
            'warning': 'exclamation-triangle',
            'danger': 'exclamation-circle',
            'error': 'exclamation-circle'
        };
        return icons[type] || 'info-circle';
    },

    // Setup AJAX defaults
    setupAjax() {
        // jQuery AJAX setup
        if (typeof $ !== 'undefined') {
            $(document).ajaxStart(() => {
                this.showLoading();
            });

            $(document).ajaxStop(() => {
                this.hideLoading();
            });

            $(document).ajaxError((event, xhr, settings, thrownError) => {
                this.handleAjaxError(xhr, thrownError);
            });
        }
    },

    // AJAX Helper
    ajax(options) {
        const defaults = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.config.csrfToken
            }
        };

        return fetch(options.url, {
            ...defaults,
            ...options,
            headers: { ...defaults.headers, ...options.headers }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            this.handleAjaxError(null, error.message);
            throw error;
        });
    },

    // Handle AJAX Errors
    handleAjaxError(xhr, error) {
        let message = 'حدث خطأ غير متوقع';
        
        if (xhr) {
            switch (xhr.status) {
                case 401:
                    message = 'غير مصرح لك بالوصول';
                    break;
                case 403:
                    message = 'ليس لديك صلاحية لهذا الإجراء';
                    break;
                case 404:
                    message = 'الصفحة المطلوبة غير موجودة';
                    break;
                case 500:
                    message = 'خطأ في الخادم';
                    break;
                default:
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
            }
        } else if (error) {
            message = error;
        }
        
        this.showAlert(message, 'error');
    },

    // Show Loading
    showLoading(target = null) {
        if (target) {
            const loader = document.createElement('div');
            loader.className = 'loading-overlay';
            loader.innerHTML = '<div class="spinner"></div>';
            target.style.position = 'relative';
            target.appendChild(loader);
        } else {
            // Global loading
            let globalLoader = document.getElementById('global-loader');
            if (!globalLoader) {
                globalLoader = document.createElement('div');
                globalLoader.id = 'global-loader';
                globalLoader.innerHTML = `
                    <div class="loading-backdrop"></div>
                    <div class="loading-content">
                        <div class="spinner"></div>
                        <div class="loading-text">جاري التحميل...</div>
                    </div>
                `;
                document.body.appendChild(globalLoader);
            }
            globalLoader.style.display = 'flex';
        }
    },

    // Hide Loading
    hideLoading(target = null) {
        if (target) {
            const loader = target.querySelector('.loading-overlay');
            if (loader) {
                loader.remove();
            }
        } else {
            const globalLoader = document.getElementById('global-loader');
            if (globalLoader) {
                globalLoader.style.display = 'none';
            }
        }
    },

    // Hide Loading Screen
    hideLoadingScreen() {
        const loadingScreen = document.getElementById('loading-screen');
        if (loadingScreen) {
            setTimeout(() => {
                loadingScreen.style.opacity = '0';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 500);
            }, 1000);
        }
    },

    // Format Number
    formatNumber(num) {
        return new Intl.NumberFormat('ar-EG').format(num);
    },

    // Format Date
    formatDate(date, format = 'short') {
        const options = {
            short: { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            },
            long: { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }
        };
        
        return new Intl.DateTimeFormat('ar-EG', options[format]).format(new Date(date));
    },

    // Debounce Function
    debounce(func, wait, immediate = false) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    },

    // Throttle Function
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    },

    // Storage Helpers
    storage: {
        set(key, value) {
            localStorage.setItem(`belqas_${key}`, JSON.stringify(value));
        },
        
        get(key, defaultValue = null) {
            const item = localStorage.getItem(`belqas_${key}`);
            return item ? JSON.parse(item) : defaultValue;
        },
        
        remove(key) {
            localStorage.removeItem(`belqas_${key}`);
        },
        
        clear() {
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('belqas_')) {
                    localStorage.removeItem(key);
                }
            });
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    BelqasApp.init();
});

// Global utility functions
window.showAlert = (message, type, duration) => BelqasApp.showAlert(message, type, duration);
window.openModal = (modalId) => BelqasApp.openModal(modalId);
window.closeModal = (modal) => BelqasApp.closeModal(modal);
window.showLoading = (target) => BelqasApp.showLoading(target);
window.hideLoading = (target) => BelqasApp.hideLoading(target);