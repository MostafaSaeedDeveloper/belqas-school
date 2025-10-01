<div class="app-topbar">
    <div class="topbar-primary">
        <div class="topbar-branding">
            <button class="topbar-toggle" id="sidebarToggle" aria-label="تبديل القائمة الجانبية">
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
            </button>
            <div class="topbar-greeting">
                <span class="topbar-eyebrow">لوحة تحكم الإدارة</span>
                <h1 class="topbar-title">مرحباً، {{ auth()->user()->name }}</h1>
                <p class="topbar-meta">{{ \Carbon\Carbon::now()->translatedFormat('l، j F Y') }}</p>
            </div>
        </div>

        <div class="topbar-insights">
            <div class="insight-chip">
                <span class="insight-label">عدد الطلاب</span>
                <strong class="insight-value" data-stat="students">0</strong>
            </div>
            <div class="insight-chip">
                <span class="insight-label">الأنشطة اليوم</span>
                <strong class="insight-value" data-stat="activities">0</strong>
            </div>
            <div class="insight-chip">
                <span class="insight-label">تنبيهات عاجلة</span>
                <strong class="insight-value alert" data-stat="alerts">0</strong>
            </div>
        </div>
    </div>

    <div class="topbar-secondary">
        <form class="topbar-search" role="search">
            <i class="fas fa-search"></i>
            <input type="search" placeholder="ابحث في كل النظام..." aria-label="البحث في لوحة التحكم">
        </form>

        <div class="topbar-actions">
            <button class="topbar-action" type="button">
                <i class="fas fa-plus"></i>
                <span>إنشاء</span>
            </button>
            <button class="topbar-action" type="button">
                <i class="fas fa-calendar"></i>
                <span>جدول</span>
            </button>
            <button class="topbar-action" type="button">
                <i class="fas fa-life-ring"></i>
                <span>دعم</span>
            </button>
        </div>

        <div class="topbar-notifications">
            <button class="notification-btn" type="button" title="الإشعارات">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>
            <button class="notification-btn" type="button" title="الوضع الليلي">
                <i class="fas fa-moon"></i>
            </button>
        </div>

        <div class="topbar-user">
            <div class="user-avatar">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="user-avatar-placeholder">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @endif
            </div>
            <div class="user-overview">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="user-role">{{ auth()->user()->getRoleNames()->first() ?? 'مستخدم' }}</span>
            </div>
            <button class="user-menu-trigger" type="button" title="خيارات الحساب">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>
</div>
