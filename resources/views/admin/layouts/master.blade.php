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

    <style>
        :root {
            --sidebar-width: 300px;
            --sidebar-collapsed-width: 96px;
            --header-height: 84px;
            --page-padding: 2.4rem;
            --surface-color: #ffffff;
            --surface-muted: #f5f7fb;
            --border-color: rgba(15, 23, 42, 0.08);
            --shadow-sm: 0 6px 18px rgba(15, 23, 42, 0.05);
            --shadow-md: 0 12px 30px rgba(15, 23, 42, 0.08);
            --gradient-primary: linear-gradient(135deg, #4f46e5, #0ea5e9);
        }

        body.dashboard-app {
            min-height: 100vh;
            background: var(--surface-muted);
            font-family: 'Cairo', sans-serif;
            color: #0f172a;
            overflow-x: hidden;
        }

        .app-layout {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            background: var(--surface-muted);
        }

        .app-topbar {
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
            padding: 1.6rem var(--page-padding) 1.3rem;
        }

        .topbar-primary,
        .topbar-secondary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .topbar-branding {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-toggle {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            border: none;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(14, 165, 233, 0.25));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            gap: 6px;
            padding: 0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .topbar-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.12);
        }

        .toggle-bar {
            width: 18px;
            height: 2.5px;
            background: #1e293b;
            border-radius: 999px;
            display: block;
        }

        .topbar-greeting {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .topbar-eyebrow {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #6366f1;
        }

        .topbar-title {
            margin: 0;
            font-size: clamp(1.35rem, 1.1rem + 0.8vw, 2rem);
            font-weight: 700;
            color: #0f172a;
        }

        .topbar-meta {
            margin: 0;
            font-size: 0.9rem;
            color: #64748b;
        }

        .topbar-insights {
            display: flex;
            align-items: stretch;
            gap: 0.75rem;
        }

        .insight-chip {
            min-width: 140px;
            background: rgba(79, 70, 229, 0.08);
            border-radius: 18px;
            padding: 0.75rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            border: 1px solid rgba(99, 102, 241, 0.12);
        }

        .insight-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #4c51bf;
        }

        .insight-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e1b4b;
        }

        .insight-value.alert {
            color: #dc2626;
        }

        .topbar-search {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: #f8fafc;
            border-radius: 18px;
            padding: 0.65rem 1rem;
            border: 1px solid rgba(15, 23, 42, 0.07);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
            flex: 1 1 280px;
            max-width: 360px;
        }

        .topbar-search input {
            border: none;
            background: transparent;
            width: 100%;
            font-size: 0.95rem;
            outline: none;
        }

        .topbar-search i {
            color: #64748b;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .topbar-action {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border: none;
            border-radius: 14px;
            padding: 0.65rem 1rem;
            background: rgba(79, 70, 229, 0.12);
            color: #4c51bf;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .topbar-action:hover {
            background: rgba(14, 165, 233, 0.15);
            color: #0f172a;
        }

        .topbar-notifications {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification-btn {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            border: none;
            background: rgba(15, 23, 42, 0.06);
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .notification-btn:hover {
            background: rgba(79, 70, 229, 0.15);
            color: #312e81;
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            left: 10px;
            background: #ef4444;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: 999px;
            padding: 0.1rem 0.4rem;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.45rem 0.6rem;
            border-radius: 18px;
            background: rgba(79, 70, 229, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.12);
        }

        .topbar-user .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            overflow: hidden;
            flex-shrink: 0;
            background: rgba(79, 70, 229, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #312e81;
        }

        .topbar-user .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-overview {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            min-width: 120px;
        }

        .user-overview .user-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e1b4b;
        }

        .user-overview .user-role {
            font-size: 0.8rem;
            color: #6366f1;
        }

        .user-menu-trigger {
            border: none;
            background: rgba(15, 23, 42, 0.05);
            color: #312e81;
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .sidebar {
            position: relative !important;
            top: auto !important;
            right: auto !important;
            left: auto !important;
            height: auto;
            min-height: 100vh;
            flex: 0 0 var(--sidebar-width);
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #111c44 0%, #0f172a 100%);
            color: #e2e8f0;
            display: flex;
            padding: 0;
            border-left: none;
            border-right: 1px solid rgba(148, 163, 184, 0.1);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.2);
            transition: width 0.3s ease, flex-basis 0.3s ease;
            z-index: 2;
            overflow: hidden;
        }

        .sidebar::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(148, 163, 255, 0.25), transparent 58%),
                        radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.22), transparent 60%);
            pointer-events: none;
            opacity: 0.9;
        }

        .sidebar-inner {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 1.75rem 1.5rem 1.5rem;
            gap: 1.25rem;
            overflow: hidden;
        }

        .sidebar-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            position: relative;
            z-index: 2;
        }

        .logo-img {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            object-fit: cover;
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.35);
        }

        .brand-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #f8fafc;
            margin: 0;
        }

        .brand-subtitle {
            font-size: 0.9rem;
            color: rgba(226, 232, 240, 0.65);
            margin: 0;
        }

        .brand-badge {
            background: rgba(14, 165, 233, 0.18);
            color: #38bdf8;
            font-weight: 600;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-size: 0.75rem;
            letter-spacing: 0.03em;
            backdrop-filter: blur(6px);
        }

        .sidebar-user-card {
            position: relative;
            padding: 1.1rem 1.1rem 1.1rem 1.35rem;
            background: rgba(15, 23, 42, 0.45);
            border: 1px solid rgba(148, 163, 184, 0.12);
            border-radius: 22px;
            display: flex;
            align-items: center;
            gap: 0.95rem;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .sidebar-user-card::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            border: 1px solid rgba(99, 102, 241, 0.15);
            opacity: 0.65;
            pointer-events: none;
        }

        .user-avatar {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
            box-shadow: 0 12px 28px rgba(14, 165, 233, 0.35);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            background: rgba(99, 102, 241, 0.35);
            color: #eef2ff;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .user-name {
            margin: 0;
            color: #f8fafc;
            font-size: 1rem;
            font-weight: 700;
        }

        .user-role {
            margin: 0;
            font-size: 0.85rem;
            color: rgba(226, 232, 240, 0.75);
        }

        .user-status {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #34d399;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #34d399;
            box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.15);
        }

        .user-actions {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            margin-right: auto;
        }

        .user-action {
            font-size: 0.78rem;
            color: rgba(226, 232, 240, 0.8);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .user-action:hover {
            color: #38bdf8;
        }

        .sidebar-quick-links {
            background: rgba(15, 23, 42, 0.4);
            border-radius: 22px;
            border: 1px solid rgba(148, 163, 184, 0.12);
            padding: 1rem 1.1rem 1.1rem;
            box-shadow: inset 0 1px 0 rgba(148, 163, 184, 0.1);
        }

        .sidebar-section-label {
            display: block;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(148, 163, 184, 0.65);
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .quick-links-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .quick-link {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            align-items: flex-start;
            justify-content: center;
            padding: 0.75rem 0.85rem;
            border-radius: 16px;
            text-decoration: none;
            background: rgba(79, 70, 229, 0.16);
            color: #eef2ff;
            font-size: 0.8rem;
            font-weight: 600;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .quick-link i {
            font-size: 0.95rem;
        }

        .quick-link:hover {
            transform: translateY(-2px);
            background: rgba(56, 189, 248, 0.2);
        }

        .nav-section {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .nav-menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.95rem;
            background: transparent;
            border-radius: 18px;
            padding: 0.85rem 1rem;
            color: rgba(226, 232, 240, 0.82);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .nav-link:hover {
            color: #f8fafc;
            background: rgba(79, 70, 229, 0.22);
            transform: translateX(-4px);
        }

        .nav-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .nav-arrow {
            margin-right: auto;
            color: rgba(148, 163, 184, 0.6);
            transition: transform 0.2s ease;
        }

        .nav-item.menu-open > .nav-link .nav-arrow {
            transform: rotate(90deg);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.35), rgba(14, 165, 233, 0.35));
            color: #f8fafc;
            box-shadow: inset 0 0 0 1px rgba(148, 163, 255, 0.3);
        }

        .nav-submenu {
            list-style: none;
            margin: 0.4rem 0 0;
            padding: 0.4rem 0 0.4rem 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            border-right: 2px solid rgba(79, 70, 229, 0.3);
            margin-right: 1.45rem;
        }

        .nav-submenu .nav-link {
            padding: 0.6rem 0.85rem 0.6rem 0.9rem;
            font-size: 0.9rem;
            background: rgba(15, 23, 42, 0.35);
            border-radius: 14px;
            color: rgba(226, 232, 240, 0.8);
        }

        .nav-submenu .nav-link:hover,
        .nav-submenu .nav-link.active {
            background: rgba(56, 189, 248, 0.2);
            color: #f8fafc;
        }

        .sidebar-extra {
            margin-top: auto;
        }

        .sidebar-support-card {
            position: relative;
            padding: 1.1rem 1.2rem;
            border-radius: 22px;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.14);
            display: flex;
            align-items: center;
            gap: 0.9rem;
        }

        .support-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(56, 189, 248, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            font-size: 1.1rem;
        }

        .support-content {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            color: rgba(226, 232, 240, 0.9);
        }

        .support-content strong {
            font-size: 0.95rem;
            font-weight: 700;
        }

        .support-content span {
            font-size: 0.8rem;
            color: rgba(148, 163, 184, 0.8);
        }

        .support-link {
            margin-right: auto;
            font-size: 0.8rem;
            font-weight: 700;
            color: #38bdf8;
            text-decoration: none;
        }

        .support-link:hover {
            text-decoration: underline;
        }

        .sidebar-footer {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            padding-top: 0.5rem;
        }

        .footer-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .footer-actions a {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: rgba(148, 163, 184, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: rgba(226, 232, 240, 0.8);
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .footer-actions a:hover {
            background: rgba(56, 189, 248, 0.25);
            color: #f8fafc;
        }

        .logout-button {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            border: none;
            border-radius: 16px;
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.95rem;
            color: #f8fafc;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.85), rgba(190, 18, 60, 0.85));
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .logout-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(239, 68, 68, 0.35);
        }

        .sidebar + .main-content {
            flex: 1;
        }

        .app-footer {
            padding: 2rem var(--page-padding) 2.4rem;
            background: transparent;
        }

        .footer-layout {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.75rem;
            background: rgba(255, 255, 255, 0.92);
            border-radius: 26px;
            padding: 1.4rem 2rem;
            border: 1px solid rgba(15, 23, 42, 0.06);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(14px);
        }

        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .footer-title {
            font-weight: 700;
            color: #0f172a;
        }

        .footer-subtitle {
            font-size: 0.85rem;
            color: #64748b;
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #475569;
            font-size: 0.88rem;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: #312e81;
        }

        .footer-meta {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .footer-status {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-weight: 600;
            color: #059669;
        }

        .footer-status .status-dot {
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.18);
        }

        .footer-version {
            font-size: 0.85rem;
            color: #64748b;
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 998;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .main-surface {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .layout-header {
            position: sticky;
            top: 0;
            z-index: 990;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        }

        .layout-main {
            flex: 1;
            padding: var(--page-padding) 0 0;
        }

        .page-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .page-container {
            padding: 0 var(--page-padding) var(--page-padding);
        }

        .page-heading {
            margin-bottom: 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .page-heading__main {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .page-heading__titles {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .page-heading__eyebrow {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6366f1;
            letter-spacing: 0.03em;
        }

        .page-heading__title {
            font-size: clamp(1.5rem, 1.2rem + 1vw, 2.35rem);
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .page-heading__subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin: 0;
            max-width: 720px;
        }

        .page-heading__meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .page-heading__actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .page-heading__actions > * {
            flex-shrink: 0;
        }

        .page-heading__actions .btn,
        .page-heading__actions a,
        .page-heading__actions button {
            border-radius: 999px;
            padding: 0.55rem 1.4rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .page-breadcrumb .breadcrumb {
            margin: 0;
            background: transparent;
            padding: 0;
        }

        .page-breadcrumb .breadcrumb-item {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .page-breadcrumb .breadcrumb-item a {
            color: #475569;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none;
        }

        .page-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            content: "\f104";
            margin: 0 0.75rem;
        }

        .content-canvas {
            background: var(--surface-color);
            border-radius: 28px;
            box-shadow: var(--shadow-sm);
            padding: clamp(1.5rem, 1.2rem + 1vw, 2.5rem);
            position: relative;
        }

        .content-canvas::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            border: 1px solid rgba(79, 70, 229, 0.08);
        }

        .content-canvas + .content-canvas {
            margin-top: 1.75rem;
        }

        #loading-screen {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.82);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1200;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        #loading-screen.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            padding: 2.5rem 3rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 24px;
            box-shadow: var(--shadow-md);
        }

        .loading-text {
            font-weight: 600;
            color: #4338ca;
        }

        body.sidebar-collapsed #sidebar {
            flex: 0 0 var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .sidebar .nav-text,
        body.sidebar-collapsed .sidebar .sidebar-section-label,
        body.sidebar-collapsed .sidebar .user-details,
        body.sidebar-collapsed .sidebar .user-actions,
        body.sidebar-collapsed .sidebar .brand-badge,
        body.sidebar-collapsed .sidebar .sidebar-quick-links,
        body.sidebar-collapsed .sidebar .sidebar-extra {
            opacity: 0;
            pointer-events: none;
        }

        body.sidebar-collapsed .sidebar .brand-text {
            display: none;
        }

        body.sidebar-collapsed .sidebar .brand-logo {
            justify-content: center;
        }

        body.sidebar-collapsed .sidebar .brand-logo .logo-img {
            width: 48px;
            height: 48px;
        }

        body.sidebar-collapsed .sidebar .logout-button {
            padding: 0.75rem;
        }

        body.sidebar-collapsed .sidebar .logout-button span {
            display: none;
        }

        @media (max-width: 1199.98px) {
            :root {
                --sidebar-width: 280px;
            }
        }

        @media (max-width: 991.98px) {
            body.dashboard-app {
                background: var(--surface-muted);
            }

            .app-layout {
                flex-direction: column;
            }

            .app-topbar {
                padding: 1.4rem 1.5rem 1.2rem;
            }

            .topbar-primary,
            .topbar-secondary {
                flex-direction: column;
                align-items: stretch;
            }

            .topbar-insights {
                width: 100%;
                justify-content: stretch;
            }

            .insight-chip {
                flex: 1;
            }

            .topbar-secondary {
                gap: 1rem;
            }

            .topbar-actions,
            .topbar-notifications,
            .topbar-user {
                width: 100%;
                justify-content: space-between;
            }

            .topbar-actions {
                order: 2;
            }

            .topbar-notifications {
                order: 3;
            }

            .topbar-user {
                order: 4;
            }

            .footer-layout {
                flex-direction: column;
                align-items: stretch;
                gap: 1.2rem;
            }

            .footer-links,
            .footer-meta {
                justify-content: space-between;
                width: 100%;
            }

            #sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                right: 0;
                width: var(--sidebar-width);
                max-width: 86vw;
                height: 100vh;
                z-index: 999;
                box-shadow: 0 0 60px rgba(15, 23, 42, 0.25);
                transform: translateX(100%);
                transition: transform 0.35s ease;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                min-height: 100vh;
            }

            .layout-header {
                position: sticky;
            }

            .page-container {
                padding: 0 1.5rem 1.5rem;
            }

            .content-canvas {
                border-radius: 20px;
            }
        }

        @media (max-width: 575.98px) {
            :root {
                --page-padding: 1.4rem;
            }

            .layout-header {
                border-radius: 0;
            }

            .page-heading__main {
                flex-direction: column;
                align-items: stretch;
            }

            .app-topbar {
                padding: 1.2rem 1.1rem 1rem;
            }

            .topbar-insights {
                flex-direction: column;
            }

            .topbar-insights .insight-chip {
                width: 100%;
            }

            .footer-links,
            .footer-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.65rem;
            }

            .page-heading__actions {
                justify-content: flex-start;
            }

            .content-canvas {
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body class="@yield('body-class', 'dashboard-app sidebar-expanded')">
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <div class="loading-text">جاري التحميل...</div>
        </div>
    </div>

    <!-- Layout Wrapper -->
    <div class="app-layout">
        <!-- Sidebar -->
        @include('admin.layouts.partials.sidebar')

        <!-- Sidebar Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="main-surface">
                <!-- Header / Topbar -->
                <header class="layout-header">
                    @include('admin.layouts.partials.topbar')
                </header>

                <!-- Page Content -->
                <main class="layout-main">
                    <div class="page-wrapper">
                        <div class="page-container container-fluid">
                            @if(View::hasSection('page-header') || View::hasSection('page-title') || View::hasSection('page-subtitle') || View::hasSection('breadcrumb') || View::hasSection('page-actions'))
                                <div class="page-heading">
                                    <div class="page-heading__main">
                                        <div class="page-heading__titles">
                                            @hasSection('page-eyebrow')
                                                <span class="page-heading__eyebrow">@yield('page-eyebrow')</span>
                                            @endif

                                            <h1 class="page-heading__title">@yield('page-title')</h1>

                                            @hasSection('page-subtitle')
                                                <p class="page-heading__subtitle">@yield('page-subtitle')</p>
                                            @endif

                                            @hasSection('page-meta')
                                                <div class="page-heading__meta">@yield('page-meta')</div>
                                            @endif
                                        </div>

                                        @hasSection('page-actions')
                                            <div class="page-heading__actions">@yield('page-actions')</div>
                                        @endif
                                    </div>

                                    @hasSection('breadcrumb')
                                        <nav class="page-breadcrumb" aria-label="breadcrumb">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                    <a href="{{ route('dashboard') }}">
                                                        <i class="fas fa-home"></i>
                                                        <span>الرئيسية</span>
                                                    </a>
                                                </li>
                                                @yield('breadcrumb')
                                            </ol>
                                        </nav>
                                    @endif
                                </div>
                            @endif

                            <!-- Flash Messages -->
                            @include('admin.layouts.partials.alerts')

                            <!-- Main Canvas -->
                            <div class="content-canvas">
                                @yield('content')
                            </div>

                            @stack('content-after')
                        </div>
                    </div>
                </main>

                <!-- Footer -->
                @include('admin.layouts.partials.footer')
            </div>
        </div>
    </div>

    <!-- Modals -->
    @stack('modals')

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
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
