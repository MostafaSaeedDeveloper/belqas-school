<div class="sidebar" id="sidebar">
    <!-- Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="brand-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="مدرسة بلقاس" class="logo-img">
            <div class="brand-text">
                <h3 class="brand-title">مدرسة بلقاس</h3>
                <p class="brand-subtitle">نظام إدارة متكامل</p>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="sidebar-user-info">
        <div class="user-avatar">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
            @else
                <div class="avatar-placeholder">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            @endif
        </div>
        <div class="user-details">
            <h4 class="user-name">{{ auth()->user()->name }}</h4>
            <p class="user-role">{{ auth()->user()->getRoleNames()->first() ?? 'مستخدم' }}</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <span class="nav-text">لوحة التحكم</span>
                </a>
            </li>

            <!-- إدارة الطلاب -->
            @can('view_students')
            <li class="nav-item has-submenu {{ request()->routeIs('students.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-user-graduate"></i>
                    <span class="nav-text">إدارة الطلاب</span>
                    <i class="nav-arrow fas fa-chevron-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> قائمة الطلاب
                    </a></li>
                    @can('create_students')
                    <li><a href="{{ route('students.create') }}" class="nav-link {{ request()->routeIs('students.create') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> إضافة طالب جديد
                    </a></li>
                    @endcan
                    <li><a href="{{ route('students.reports') }}" class="nav-link {{ request()->routeIs('students.reports') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> تقارير الطلاب
                    </a></li>
                </ul>
            </li>
            @endcan

            <!-- إدارة المعلمين -->
            @can('view_teachers')
            <li class="nav-item has-submenu {{ request()->routeIs('teachers.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                    <span class="nav-text">إدارة المعلمين</span>
                    <i class="nav-arrow fas fa-chevron-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.index') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> قائمة المعلمين
                    </a></li>
                    @can('create_teachers')
                    <li><a href="{{ route('teachers.create') }}" class="nav-link {{ request()->routeIs('teachers.create') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> إضافة معلم جديد
                    </a></li>
                    @endcan
                    <li><a href="{{ route('teachers.schedules') }}" class="nav-link {{ request()->routeIs('teachers.schedules') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> جداول المعلمين
                    </a></li>
                </ul>
            </li>
            @endcan

            <!-- الفصول الدراسية -->
            @can('view_classes')
            <li class="nav-item has-submenu {{ request()->routeIs('classes.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-school"></i>
                    <span class="nav-text">الفصول الدراسية</span>
                    <i class="nav-arrow fas fa-chevron-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.index') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> قائمة الفصول
                    </a></li>
                    @can('create_classes')
                    <li><a href="{{ route('classes.create') }}" class="nav-link {{ request()->routeIs('classes.create') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> إضافة فصل جديد
                    </a></li>
                    @endcan
                    <li><a href="{{ route('classes.timetables') }}" class="nav-link {{ request()->routeIs('classes.timetables') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> الجداول الدراسية
                    </a></li>
                </ul>
            </li>
            @endcan

            <!-- المواد الدراسية -->
            @can('view_subjects')
            <li class="nav-item has-submenu {{ request()->routeIs('subjects.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-book"></i>
                    <span class="nav-text">المواد الدراسية</span>
                    <i class="nav-arrow fas fa-chevron-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.index') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> قائمة المواد
                    </a></li>
                    @can('create_subjects')
                    <li><a href="{{ route('subjects.create') }}" class="nav-link {{ request()->routeIs('subjects.create') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> إضافة مادة جديدة
                    </a></li>
                    @endcan
                    <li><a href="{{ route('subjects.assignments') }}" class="nav-link {{ request()->routeIs('subjects.assignments') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> تكليف المواد
                    </a></li>
                </ul>
            </li>
            @endcan

            <!-- الحضور والغياب -->
            @can('view_attendance')
            <li class="nav-item has-submenu {{ request()->routeIs('attendance.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-calendar-check"></i>
                    <span class="nav-text">الحضور والغياب</span>
                    <i class="nav-arrow fas fa-chevron-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="{{ route('attendance.daily') }}" class="nav-link {{ request()->routeIs('attendance.daily') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> الحضور اليومي
                    </a></li>
                    <li><a href="{{ route('attendance.reports') }}" class="nav-link {{ request()->routeIs('attendance.reports') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> تقارير الحضور
                    </a></li>
                    <li><a href="{{ route('attendance.statistics') }}" class="nav-link {{ request()->routeIs('attendance.statistics') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> إحصائيات الحضور
                    </a></li>
                </ul>
            </li>
            @endcan

            <!-- الدرجات والامتحانات -->
            @can('view_grades')
            <li class="nav-item has-submenu {{ request()->routeIs('grades.*') || request()->routeIs('exams.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-graduation-cap"></i>
                    <span class="nav-text">الدرجات والامتحانات</span>
                    <i class="nav-arrow fas fa-chevron-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="{{ route('exams.index') }}" class="nav-link {{ request()->routeIs('exams.index') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> الامتحانات
                    </a></li>
                    <li><a href="{{ route('grades.input') }}" class="nav-link {{ request()->routeIs('grades.input') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> إدخال الدرجات
                    </a></li>
                    <li><a href="{{ route('grades.reports') }}" class="nav-link {{ request()->routeIs('grades.reports') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> كشوف الدرجات
                    </a></li>
                </ul>
            </li>
            @endcan

            <!-- المالية -->
            @can('view_finance')
            <li class="nav-item has-submenu {{ request()->routeIs('finance.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-money-bill-wave"></i>
                    <span class="nav-text">الشؤون المالية</span>
                    <i class="nav-arrow fas fa-chevron-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="{{ route('finance.fees') }}" class="nav-link {{ request()->routeIs('finance.fees') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> الرسوم الدراسية
                    </a></li>
                    <li><a href="{{ route('finance.payments') }}" class="nav-link {{ request()->routeIs('finance.payments') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> المدفوعات
                    </a></li>
                    <li><a href="{{ route('finance.reports') }}" class="nav-link {{ request()->routeIs('finance.reports') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> التقارير المالية
                    </a></li>
                </ul>
            </li>
            @endcan

            <!-- التقارير -->
            @can('view_reports')
            <li class="nav-item has-submenu {{ request()->routeIs('reports.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <span class="nav-text">التقارير</span>
                    <i class="nav-arrow fas fa-chevron-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="{{ route('reports.academic') }}" class="nav-link {{ request()->routeIs('reports.academic') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> التقارير الأكاديمية
                    </a></li>
                    <li><a href="{{ route('reports.attendance') }}" class="nav-link {{ request()->routeIs('reports.attendance') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> تقارير الحضور
                    </a></li>
                    <li><a href="{{ route('reports.financial') }}" class="nav-link {{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> التقارير المالية
                    </a></li>
                </ul>
            </li>
            @endcan

            <!-- إعدادات النظام -->
            @can('view_settings')
            <li class="nav-item has-submenu {{ request()->routeIs('settings.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cog"></i>
                    <span class="nav-text">إعدادات النظام</span>
                    <i class="nav-arrow fas fa-chevron-left"></i>
                </a>
                <ul class="nav-submenu">
                    <li><a href="{{ route('settings.general') }}" class="nav-link {{ request()->routeIs('settings.general') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> الإعدادات العامة
                    </a></li>
                    <li><a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> إدارة المستخدمين
                    </a></li>
                    <li><a href="{{ route('settings.permissions') }}" class="nav-link {{ request()->routeIs('settings.permissions') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> الصلاحيات
                    </a></li>
                    <li><a href="{{ route('settings.backup') }}" class="nav-link {{ request()->routeIs('settings.backup') ? 'active' : '' }}">
                        <i class="far fa-circle"></i> النسخ الاحتياطي
                    </a></li>
                </ul>
            </li>
            @endcan
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="footer-links">
            <a href="{{ route('profile.show') }}" title="الملف الشخصي">
                <i class="fas fa-user"></i>
            </a>
            <a href="{{ route('settings.general') }}" title="الإعدادات">
                <i class="fas fa-cog"></i>
            </a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="تسجيل الخروج">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
