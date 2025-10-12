class Dashboard {
    constructor() {
        this.state = {
            stats: {},
            attendanceSummary: {},
            studentTrend: {},
            attendanceTrend: [],
            studentGrowth: [],
        };

        this.statElements = document.querySelectorAll('[data-stat]');
        this.searchInput = document.querySelector('.search-input');
        this.searchResults = document.querySelector('[data-search-results]');
        this.attendanceList = document.querySelector('[data-attendance-trend]');
        this.studentGrowthList = document.querySelector('[data-student-growth]');
        this.attendancePercentageElements = document.querySelectorAll('[data-attendance-percentage]');
        this.attendanceProgressBars = document.querySelectorAll('[data-attendance-progress]');
        this.attendanceValueElements = document.querySelectorAll('[data-attendance]');

        this.debounceTimer = null;
        this.refreshInterval = null;

        this.init();
    }

    init() {
        this.applyBootstrapData();
        this.bindEvents();
        this.refreshStats();
        this.startAutoRefresh();
    }

    applyBootstrapData() {
        if (!window.dashboardBootstrap) {
            return;
        }

        this.state = {
            ...this.state,
            ...window.dashboardBootstrap,
        };

        this.updateStats(this.state.stats);
        this.updateAttendanceSummary(this.state.attendanceSummary);
        this.renderAttendanceTrend(this.state.attendanceTrend);
        this.renderStudentGrowth(this.state.studentGrowth);
    }

    bindEvents() {
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (event) => this.handleSearchInput(event));
            this.searchInput.addEventListener('focus', () => {
                if (this.searchResults && this.searchResults.childElementCount > 0) {
                    this.toggleSearchResults(true);
                }
            });
        }

        document.addEventListener('click', (event) => {
            if (!this.searchResults) {
                return;
            }

            if (!this.searchResults.contains(event.target) && event.target !== this.searchInput) {
                this.toggleSearchResults(false);
            }
        });
    }

    startAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }

        this.refreshInterval = setInterval(() => this.refreshStats(), 300000);
    }

    async refreshStats() {
        try {
            const response = await fetch('/api/dashboard/stats', {
                headers: {
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            this.state = {
                ...this.state,
                ...data,
            };

            this.updateStats(data.stats || {});
            this.updateAttendanceSummary(data.attendanceSummary || {});
            this.renderAttendanceTrend(data.attendanceTrend || []);
            this.renderStudentGrowth(data.studentGrowth || []);
        } catch (error) {
            console.warn('فشل تحميل بيانات لوحة التحكم', error);
        }
    }

    updateStats(stats = {}) {
        this.statElements.forEach((element) => {
            const key = element.dataset.stat;
            if (!key || typeof stats[key] === 'undefined' || stats[key] === null) {
                return;
            }

            const value = stats[key];
            element.textContent = typeof value === 'number'
                ? value.toLocaleString('ar-EG')
                : value;
        });
    }

    updateAttendanceSummary(summary = {}) {
        this.attendancePercentageElements.forEach((element) => {
            if (typeof summary.percentage === 'number') {
                element.textContent = `${summary.percentage}%`;
            } else {
                element.textContent = '—';
            }
        });

        this.attendanceProgressBars.forEach((element) => {
            const width = typeof summary.percentage === 'number' ? Math.min(summary.percentage, 100) : 0;
            element.style.width = `${width}%`;
        });

        this.attendanceValueElements.forEach((element) => {
            const key = element.dataset.attendance;
            if (!key || typeof summary[key] === 'undefined') {
                return;
            }

            const value = summary[key];
            element.textContent = Number.isFinite(value)
                ? Number(value).toLocaleString('ar-EG')
                : value;
        });
    }

    renderAttendanceTrend(items) {
        if (!this.attendanceList) {
            return;
        }

        const list = Array.isArray(items) ? items : [];
        this.attendanceList.innerHTML = '';

        if (list.length === 0) {
            const empty = document.createElement('li');
            empty.className = 'empty';
            empty.textContent = 'لا توجد بيانات حضور للأيام الماضية.';
            this.attendanceList.appendChild(empty);
            return;
        }

        list.forEach((item) => {
            const li = document.createElement('li');
            const percentage = typeof item.percentage === 'number' ? `${item.percentage}%` : '—';
            li.innerHTML = `<span>${item.label}</span><strong>${percentage}</strong>`;
            this.attendanceList.appendChild(li);
        });
    }

    renderStudentGrowth(items) {
        if (!this.studentGrowthList) {
            return;
        }

        const list = Array.isArray(items) ? items : [];
        this.studentGrowthList.innerHTML = '';

        if (list.length === 0) {
            const empty = document.createElement('li');
            empty.className = 'empty';
            empty.textContent = 'لا توجد بيانات تسجيل حديثة.';
            this.studentGrowthList.appendChild(empty);
            return;
        }

        list.forEach((item) => {
            const li = document.createElement('li');
            li.innerHTML = `<span>${item.label}</span><strong>${this.formatNumber(item.count)}</strong>`;
            this.studentGrowthList.appendChild(li);
        });
    }

    handleSearchInput(event) {
        const query = event.target.value.trim();

        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }

        if (query.length < 2) {
            this.clearSearchResults();
            return;
        }

        this.debounceTimer = setTimeout(() => {
            this.performSearch(query);
        }, 250);
    }

    async performSearch(query) {
        if (!this.searchResults) {
            return;
        }

        this.renderSearchLoading();
        this.toggleSearchResults(true);

        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const results = await response.json();
            this.renderSearchResults(Array.isArray(results) ? results : []);
        } catch (error) {
            console.error('فشل البحث في النظام', error);
            this.renderSearchError();
        }
    }

    renderSearchLoading() {
        if (!this.searchResults) {
            return;
        }

        this.searchResults.innerHTML = '<div class="search-suggestion is-loading">جاري البحث...</div>';
    }

    renderSearchResults(results) {
        if (!this.searchResults) {
            return;
        }

        this.searchResults.innerHTML = '';

        if (results.length === 0) {
            this.renderSearchEmpty();
            return;
        }

        const fragment = document.createDocumentFragment();

        results.forEach((item) => {
            const elementTag = item.url ? 'a' : 'div';
            const suggestion = document.createElement(elementTag);
            suggestion.className = 'search-suggestion';
            if (item.url) {
                suggestion.href = item.url;
            }

            suggestion.innerHTML = `
                <div class="search-suggestion-title">${item.name}</div>
                <div class="search-suggestion-meta">
                    <span>${item.role ?? 'مستخدم'}</span>
                    ${item.email ? `<span>${item.email}</span>` : ''}
                </div>
            `;

            fragment.appendChild(suggestion);
        });

        this.searchResults.appendChild(fragment);
    }

    renderSearchEmpty() {
        if (!this.searchResults) {
            return;
        }

        this.searchResults.innerHTML = '<div class="search-suggestion is-empty">لا توجد نتائج مطابقة.</div>';
    }

    renderSearchError() {
        if (!this.searchResults) {
            return;
        }

        this.searchResults.innerHTML = '<div class="search-suggestion is-error">تعذر تحميل نتائج البحث، حاول مرة أخرى.</div>';
    }

    clearSearchResults() {
        if (!this.searchResults) {
            return;
        }

        this.searchResults.innerHTML = '';
        this.toggleSearchResults(false);
    }

    toggleSearchResults(show) {
        if (!this.searchResults) {
            return;
        }

        this.searchResults.hidden = !show;
    }

    formatNumber(value) {
        const numericValue = Number(value);
        if (!Number.isFinite(numericValue)) {
            return value ?? '—';
        }

        return numericValue.toLocaleString('ar-EG');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.dashboard-hero')) {
        window.belqasDashboard = new Dashboard();
    }
});
