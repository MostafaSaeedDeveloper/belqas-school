<footer class="main-footer">
    <div class="footer-content">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="footer-text">
                        © {{ date('Y') }} مدرسة بلقاس - المنصه هى مساهمه مجانيه من ولي أمر الطلاب (مازن و آسر ) محمد السيد على
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="footer-links">
                        <a href="{{ route('dashboard') }}" class="footer-link">الرئيسية</a>
                        <a href="#" class="footer-link">المساعدة</a>
                        <a href="#" class="footer-link">الدعم الفني</a>
                        <a href="#" class="footer-link">سياسة الخصوصية</a>
                    </div>
                    <div class="footer-version">
                        النسخة 1.0.0
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.main-footer {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    padding: var(--spacing-lg) 0;
    margin-top: auto;
}

.footer-text {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

.footer-links {
    display: flex;
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-sm);
    justify-content: flex-end;
}

.footer-link {
    color: #666;
    text-decoration: none;
    font-size: 0.9rem;
    transition: var(--transition-fast);
}

.footer-link:hover {
    color: var(--primary-color);
}

.footer-version {
    color: #999;
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .main-footer {
        text-align: center;
    }

    .footer-links {
        justify-content: center;
        flex-wrap: wrap;
        gap: var(--spacing-md);
    }

    .col-md-6 {
        text-align: center !important;
        margin-bottom: var(--spacing-sm);
    }
}
</style>
