@php
    $method = $method ?? 'POST';
    $submitLabel = $submitLabel ?? 'حفظ';
    $typeOptions = [
        'announcement' => 'إعلان إداري',
        'news' => 'خبر مدرسي',
    ];
@endphp

<form action="{{ $action }}" method="POST" class="card shadow-sm">
    @csrf
    @unless(strtoupper($method) === 'POST')
        @method($method)
    @endunless

    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-8">
                <div class="mb-4">
                    <label for="title" class="form-label">عنوان الخبر أو الإعلان<span class="text-danger">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}" class="form-control" required>
                    <div class="form-text">اجعل العنوان واضحاً ومعبراً عن مضمون الرسالة.</div>
                </div>

                <div class="mb-4">
                    <label for="summary" class="form-label">ملخص مختصر</label>
                    <textarea id="summary" name="summary" rows="2" class="form-control" placeholder="سطر أو سطران يوضّحان الفكرة الأساسية">{{ old('summary', $announcement->summary) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="content" class="form-label">نص الرسالة الكامل<span class="text-danger">*</span></label>
                    <textarea id="content" name="content" rows="8" class="form-control" required>{{ old('content', $announcement->content) }}</textarea>
                    <div class="form-text">يمكنك استخدام فقرات، نقاط، أو تفاصيل إضافية كما تحتاج.</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-4">
                    <label for="type" class="form-label">نوع الرسالة<span class="text-danger">*</span></label>
                    <select id="type" name="type" class="form-select" required>
                        @foreach($typeOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $announcement->type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="published_at" class="form-label">تاريخ النشر</label>
                    <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at', optional($announcement->published_at)->format('Y-m-d\TH:i')) }}" class="form-control">
                    <div class="form-text">اترك الحقل فارغاً لاستخدام وقت الحفظ الحالي.</div>
                </div>

                <div class="mb-4">
                    <label for="expires_at" class="form-label">تاريخ الانتهاء (اختياري)</label>
                    <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at', optional($announcement->expires_at)->format('Y-m-d\TH:i')) }}" class="form-control">
                    <div class="form-text">سيختفي الإعلان تلقائياً بعد هذا التاريخ.</div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $announcement->is_published))>
                    <label class="form-check-label" for="is_published">نشر فوراً</label>
                </div>

                <div class="alert alert-light border">
                    <h6 class="fw-bold mb-2"><i class="fas fa-info-circle text-primary"></i> تلميحات سريعة</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-1">استخدم نوع "خبر" للأحداث والإنجازات المدرسية.</li>
                        <li class="mb-1">استخدم نوع "إعلان" للتعميمات الإدارية والتنبيهات.</li>
                        <li>يمكنك تحديد تاريخ انتهاء لإخفاء الرسالة بعد وقت محدد.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center bg-light">
        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right"></i>
            رجوع للقائمة
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            {{ $submitLabel }}
        </button>
    </div>
</form>
