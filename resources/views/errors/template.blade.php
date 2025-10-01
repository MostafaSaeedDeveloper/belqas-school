<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'حدث خطأ')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light dark;
            --background: #f8fafc;
            --foreground: #0f172a;
            --muted: #64748b;
            --accent: #2563eb;
            --card-bg: rgba(255, 255, 255, 0.9);
            --shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --background: #0f172a;
                --foreground: #e2e8f0;
                --muted: #94a3b8;
                --accent: #60a5fa;
                --card-bg: rgba(15, 23, 42, 0.88);
                --shadow: 0 20px 55px rgba(8, 15, 32, 0.45);
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Cairo', 'Tajawal', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, rgba(37, 99, 235, 0.08), transparent 55%), var(--background);
            color: var(--foreground);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
        }

        main.error-wrapper {
            width: min(560px, 100%);
        }

        .error-card {
            background-color: var(--card-bg);
            border-radius: 28px;
            padding: 3rem 2.5rem;
            box-shadow: var(--shadow);
            backdrop-filter: blur(16px);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .error-card::before {
            content: '';
            position: absolute;
            inset-inline-start: -40px;
            inset-block-start: -120px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.12), transparent 65%);
            z-index: 0;
        }

        .error-card > * {
            position: relative;
            z-index: 1;
        }

        .error-code {
            font-size: clamp(3rem, 9vw, 4.5rem);
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 1.25rem;
        }

        .error-title {
            font-size: clamp(1.5rem, 5vw, 2rem);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.05rem;
            color: var(--muted);
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .error-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 1.75rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            color: #fff;
            background-image: linear-gradient(135deg, var(--accent), #3b82f6);
            box-shadow: 0 12px 25px rgba(37, 99, 235, 0.28);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            width: min(260px, 100%);
        }

        .btn:hover,
        .btn:focus {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(37, 99, 235, 0.32);
        }

        .btn.secondary {
            background-image: none;
            background-color: transparent;
            color: var(--accent);
            border: 1px solid rgba(37, 99, 235, 0.4);
            box-shadow: none;
        }

        .btn.secondary:hover,
        .btn.secondary:focus {
            background-color: rgba(37, 99, 235, 0.08);
            transform: none;
        }

        details.error-details {
            margin-top: 2.5rem;
            text-align: start;
            background-color: rgba(15, 23, 42, 0.04);
            border-radius: 18px;
            padding: 1.25rem 1.5rem;
            max-height: 340px;
            overflow: auto;
            direction: ltr;
        }

        details.error-details summary {
            cursor: pointer;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 1rem;
        }

        details.error-details pre {
            margin: 0;
            white-space: pre-wrap;
            font-family: 'Fira Code', 'Cascadia Code', 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.5;
            color: var(--foreground);
        }

        @media (max-width: 640px) {
            body {
                padding: 2rem 1rem;
            }

            .error-card {
                padding: 2.5rem 1.75rem;
            }

            .error-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<main class="error-wrapper">
    <section class="error-card" role="alert">
        <div class="error-code">@yield('code', $status ?? 'خطأ')</div>
        <h1 class="error-title">@yield('heading', $title ?? 'حدث خطأ غير متوقع')</h1>
        <p class="error-message">@yield('message', $message ?? 'نأسف، حدث خطأ غير متوقع. يرجى المحاولة لاحقاً.')</p>
        <div class="error-actions">
            <a href="{{ url()->previous() }}" class="btn">@yield('backLabel', 'العودة للخلف')</a>
            <a href="{{ $homeUrl ?? url('/dashboard') }}" class="btn secondary">@yield('homeLabel', 'الانتقال للوحة التحكم')</a>
        </div>
        @hasSection('trace')
            <details class="error-details">
                <summary>@yield('traceSummary', 'عرض التفاصيل التقنية')</summary>
                <pre>@yield('trace')</pre>
            </details>
        @elseif(!empty($trace))
            <details class="error-details">
                <summary>@yield('traceSummary', 'عرض التفاصيل التقنية')</summary>
                <pre>{{ $trace }}</pre>
            </details>
        @endif
    </section>
</main>
</body>
</html>
