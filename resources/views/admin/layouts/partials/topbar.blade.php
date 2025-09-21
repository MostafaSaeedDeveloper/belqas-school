<div class="top-bar">
    <div class="welcome-section">
        <div class="welcome-msg">
            <h1>مرحباً بك، {{ auth()->user()->name }}</h1>
            <p>{{ \Carbon\Carbon::now()->translatedFormat('l، j F Y') }}</p>
        </div>
    </div>

    <div class="top-bar-actions">
        <!-- Search Box -->
        <div class="search-box">
            <input type="text" class="search-input" placeholder="البحث في النظام...">
            <i class="search-icon fas fa-search"></i>
        </div>

        <!-- Notifications -->
        <button class="notification-btn" title="الإشعارات">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </button>

        <!-- User Info -->
        <div class="user-info">
            <div class="user-avatar">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="user-avatar-placeholder">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @endif
            </div>
            <div class="user-details">
                <h3>{{ auth()->user()->name }}</h3>
                <p>{{ auth()->user()->getRoleNames()->first() ?? 'مستخدم' }}</p>
            </div>
            <button class="profile-btn" title="الملف الشخصي">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>
</div>
