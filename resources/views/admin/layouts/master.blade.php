<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'نظام إدارة مدرسة بلقاس')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Core CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/core.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">

    <!-- Page Specific CSS -->
    @stack('styles')

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>
<body class="@yield('body-class', 'app-modern-dashboard')">
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <div class="loading-text">جاري التحميل...</div>
        </div>
    </div>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Main Wrapper -->
    <div class="app-shell">
        <!-- Sidebar -->
        @include('admin.layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="app-main main-content">
            <!-- Top Navigation -->
            @include('admin.layouts.partials.topbar')

            <!-- Page Content -->
            <div class="page-wrapper">
                @hasSection('page-header')
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-header-info">
                                <h1 class="page-title">@yield('page-title')</h1>
                                @hasSection('page-subtitle')
                                    <p class="page-subtitle">@yield('page-subtitle')</p>
                                @endif
                            </div>
                            <nav aria-label="breadcrumb" class="page-breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">
                                            <i class="fas fa-home"></i> الرئيسية
                                        </a>
                                    </li>
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif

                <!-- Main Content Area -->
                <div class="content-area">
                    <!-- Flash Messages -->
                    @include('admin.layouts.partials.alerts')

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            @include('admin.layouts.partials.footer')
        </div>
    </div>

    <!-- Modals -->
    @stack('modals')

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core Scripts -->
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    <!-- Page Specific Scripts -->
    @stack('scripts')

    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <!-- Inline Scripts -->
    @stack('inline-scripts')
</body>
</html>
