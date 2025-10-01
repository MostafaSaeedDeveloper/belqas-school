<header class="app-header">
    <div class="header-leading">
        <button class="icon-btn" id="sidebarToggle" aria-label="فتح وإغلاق القائمة">
            <i class="fas fa-bars"></i>
        </button>
        <div class="header-greeting">
            <span class="greeting-subtitle">مرحباً بك مجدداً</span>
            <h1 class="greeting-title">{{ auth()->user()->name }}</h1>
            <p class="greeting-date">{{ \Carbon\Carbon::now()->translatedFormat('l، j F Y') }}</p>
        </div>
    </div>

    <div class="header-tools">
        <div class="search-field">
            <i class="fas fa-search"></i>
            <input type="text" class="search-input" placeholder="البحث السريع داخل النظام">
        </div>

        <div class="header-actions">
            <button class="icon-btn notification-btn" title="الإشعارات">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>
            <button class="icon-btn" title="المهام">
                <i class="fas fa-clipboard-check"></i>
            </button>
            <button class="icon-btn" title="المساعدة">
                <i class="fas fa-circle-question"></i>
            </button>
        </div>

        <div class="header-profile">
            <div class="profile-avatar">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                @else
                    <span>{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                @endif
            </div>
            <div class="profile-info">
                <span class="profile-role">{{ auth()->user()->getRoleNames()->first() ?? 'مستخدم' }}</span>
                <strong class="profile-name">{{ auth()->user()->name }}</strong>
            </div>
            <button class="icon-btn profile-btn" title="خيارات المستخدم">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>
</header>
