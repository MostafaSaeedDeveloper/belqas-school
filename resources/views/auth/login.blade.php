<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول - نظام إدارة مدرسة بلقاس</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            direction: rtl;
            text-align: right;
            display: flex;
            justify-content: center;
        }

        .page-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px 20px;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            margin: auto 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #fff;
            font-size: 1rem;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #fff;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            padding-right: 50px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            background: white;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        .form-icon {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-control:focus + .form-icon {
            color: #667eea;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 8px;
            display: block;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .login-footer {
            margin-top: auto;
            padding-top: 20px;
            padding-bottom: 10px;
            text-align: center;
            color: #fff;
            font-size: 0.95rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .login-footer i {
            color: #dc3545;
            margin-inline-end: 6px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        .remember-me label {
            color: #666;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .login-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-text {
            position: relative;
            z-index: 1;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
            color: #999;
            font-size: 0.9rem;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e1e5e9;
            z-index: 1;
        }

        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        .demo-accounts {
            background: #f8f9ff;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .demo-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .demo-list {
            display: grid;
            gap: 8px;
            font-size: 0.85rem;
        }

        .demo-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e1e5e9;
        }

        .demo-username {
            font-weight: 600;
            color: #667eea;
        }

        .demo-role {
            color: #666;
        }

        .loading {
            display: none;
        }

        .login-btn.loading .btn-text {
            opacity: 0;
        }

        .login-btn.loading .loading {
            display: inline-block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Animations */
        .login-card {
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 15px;
            }

            .login-card {
                padding: 30px 25px;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .demo-list {
                font-size: 0.8rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .login-card {
                background: rgba(30, 30, 30, 0.95);
                color: #e1e1e1;
            }

            .form-control {
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.2);
                color: #e1e1e1;
            }

            .form-control::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="logo">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h1 class="login-title"> مدرسة بلقاس المتميزة للغات</h1>
                    <p class="login-subtitle">Belqas O.D.L. School</p>
                </div>

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="username" class="form-label">اسم المستخدم</label>
                        <div style="position: relative;">
                            <input
                                id="username"
                                type="text"
                                class="form-control @error('username') is-invalid @enderror"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autocomplete="username"
                                autofocus
                                placeholder="أدخل اسم المستخدم"
                            >
                            <i class="form-icon fas fa-user"></i>
                        </div>
                        @error('username')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <div style="position: relative;">
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="أدخل كلمة المرور"
                            >
                            <i class="form-icon fas fa-lock"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                تذكرني
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="login-btn" id="loginBtn">
                        <span class="btn-text">تسجيل الدخول</span>
                        <div class="loading">
                            <div class="spinner"></div>
                        </div>
                    </button>
                </form>

                <div class="divider">
                    <span>أو جرب الحسابات التجريبية</span>
                </div>

                <div class="demo-accounts">
                    <div class="demo-title">حسابات تجريبية للاختبار</div>
                    <div class="demo-list">
                        <div class="demo-item">
                            <span class="demo-username">admin</span>
                            <span class="demo-role">مدير عام</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-username">principal</span>
                            <span class="demo-role">مدير المدرسة</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-username">teacher</span>
                            <span class="demo-role">معلم</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-username">student</span>
                            <span class="demo-role">طالب</span>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 10px; font-size: 0.8rem; color: #666;">
                        كلمة المرور للجميع: <strong>123456789</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-footer">
            <i class="fas fa-heart" aria-hidden="true"></i>
            <span>مساهمه مجانيه من ولي أمر الطلاب (مازن و آسر ) محمد السيد على</span>
        </div>
    </div>

    <script>
        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
        });

        // Demo account quick login
        document.querySelectorAll('.demo-item').forEach(item => {
            item.addEventListener('click', function() {
                const username = this.querySelector('.demo-username').textContent;
                document.getElementById('username').value = username;
                document.getElementById('password').value = '123456789';

                // Add visual feedback
                this.style.background = '#667eea';
                this.style.color = 'white';
                setTimeout(() => {
                    this.style.background = 'white';
                    this.style.color = '';
                }, 200);
            });
        });

        // Enhanced form interactions
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Error handling and user feedback
        @if ($errors->any())
            setTimeout(() => {
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.focus();
                    firstError.parentElement.style.animation = 'shake 0.5s ease-in-out';
                }
            }, 100);
        @endif

        // Shake animation for errors
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
