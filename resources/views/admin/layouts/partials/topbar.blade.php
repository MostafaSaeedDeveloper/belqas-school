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

        @php
            $user = auth()->user();
            $canLoadNotifications = method_exists($user, 'notifications');
            $notifications = $canLoadNotifications
                ? $user->notifications()->latest()->limit(6)->get()
                : collect();
            $unreadNotificationsCount = $canLoadNotifications
                ? $user->unreadNotifications()->count()
                : 0;
        @endphp

        <div class="header-actions">
            <div class="dropdown notifications-dropdown">
                <button class="icon-btn notification-btn" data-toggle="notifications" aria-haspopup="true" aria-expanded="false" title="الإشعارات">
                    <i class="fas fa-bell"></i>
                    @if($unreadNotificationsCount > 0)
                        <span class="notification-badge">{{ $unreadNotificationsCount }}</span>
                    @endif
                </button>
                <div class="dropdown-panel notifications-panel" id="notificationDropdown" role="menu" aria-hidden="true">
                    <div class="dropdown-header">
                        <div>
                            <h3>الإشعارات</h3>
                            <span class="dropdown-subtitle">آخر التحديثات داخل النظام</span>
                        </div>
                        <a href="{{ route('events.index') }}" class="dropdown-action">عرض الكل</a>
                    </div>
                    <div class="dropdown-body">
                        @forelse($notifications as $notification)
                            <a href="#" class="notification-item {{ $notification->read_at ? '' : 'is-unread' }}" role="menuitem">
                                <div class="notification-icon">
                                    <i class="fas fa-circle"></i>
                                </div>
                                <div class="notification-content">
                                    <span class="notification-title">{{ $notification->data['title'] ?? 'تنبيه جديد' }}</span>
                                    @if(isset($notification->data['message']))
                                        <span class="notification-text">{{ $notification->data['message'] }}</span>
                                    @endif
                                    <span class="notification-time">{{ $notification->created_at?->diffForHumans() }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-bell-slash"></i></div>
                                <p>لا توجد إشعارات جديدة حالياً</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
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
            <div class="dropdown profile-dropdown">
                <button class="icon-btn profile-btn" data-toggle="profile-menu" aria-haspopup="true" aria-expanded="false" title="خيارات المستخدم">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-panel profile-panel" id="profileDropdown" role="menu" aria-hidden="true">
                    <div class="dropdown-header">
                        <div>
                            <h3>{{ auth()->user()->name }}</h3>
                            <span class="dropdown-subtitle">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    <div class="dropdown-body">
                        <a href="{{ route('profile.show') }}" class="dropdown-link" role="menuitem">
                            <i class="fas fa-user"></i>
                            الملف الشخصي
                        </a>
                        <a href="{{ route('profile.edit') }}" class="dropdown-link" role="menuitem">
                            <i class="fas fa-user-edit"></i>
                            تعديل البيانات
                        </a>
                        <hr>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-link text-danger" role="menuitem">
                                <i class="fas fa-arrow-right-from-bracket"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
