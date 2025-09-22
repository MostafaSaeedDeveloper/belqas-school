@php($formStudent = $student ?? null)

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">اسم الطالب الكامل <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $formStudent?->name) }}" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">اسم الدخول <span class="text-danger">*</span></label>
        <input type="text" name="username" value="{{ old('username', $formStudent?->username) }}" class="form-control" dir="ltr" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
        <input type="email" name="email" value="{{ old('email', $formStudent?->email) }}" class="form-control" dir="ltr" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">رقم الهاتف</label>
        <input type="text" name="phone" value="{{ old('phone', $formStudent?->phone) }}" class="form-control" dir="ltr">
    </div>

    <div class="col-md-6">
        <label class="form-label">كلمة المرور{{ isset($formStudent) && $formStudent?->exists ? ' (اختياري)' : '' }} <span class="text-danger">{{ isset($formStudent) && $formStudent?->exists ? '' : '*' }}</span></label>
        <input type="password" name="password" class="form-control" {{ isset($formStudent) && $formStudent?->exists ? '' : 'required' }}>
        <small class="text-muted">{{ isset($formStudent) && $formStudent?->exists ? 'اترك الحقل فارغاً في حال عدم الرغبة بتغيير كلمة المرور.' : 'الحد الأدنى 8 أحرف.' }}</small>
    </div>

    <div class="col-md-6">
        <label class="form-label">تأكيد كلمة المرور {{ isset($formStudent) && $formStudent?->exists ? '(اختياري)' : '' }}</label>
        <input type="password" name="password_confirmation" class="form-control" {{ isset($formStudent) && $formStudent?->exists ? '' : 'required' }}>
    </div>

    <div class="col-md-6">
        <label class="form-label">صورة الطالب</label>
        <input type="file" name="avatar" class="form-control" accept="image/*">
        <small class="text-muted">الحد الأقصى للحجم 2 ميجابايت.</small>
        @if($formStudent?->avatar)
            <div class="mt-2 d-flex align-items-center gap-3">
                <img src="{{ $formStudent->avatar_url }}" alt="صورة الطالب" class="rounded-circle" width="48" height="48">
                <span class="text-muted">الصورة الحالية</span>
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">حالة الحساب</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="student-active" name="active" value="1" @checked(old('active', $formStudent?->active ?? true))>
            <label class="form-check-label" for="student-active">{{ old('active', $formStudent?->active ?? true) ? 'نشط' : 'غير نشط' }}</label>
        </div>
    </div>
</div>
