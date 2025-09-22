@php($formUser = $user ?? null)

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $formUser?->name) }}" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">اسم الدخول <span class="text-danger">*</span></label>
        <input type="text" name="username" value="{{ old('username', $formUser?->username) }}" class="form-control" dir="ltr" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
        <input type="email" name="email" value="{{ old('email', $formUser?->email) }}" class="form-control" dir="ltr" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">رقم الهاتف</label>
        <input type="text" name="phone" value="{{ old('phone', $formUser?->phone) }}" class="form-control" dir="ltr">
    </div>

    <div class="col-md-6">
        <label class="form-label">كلمة المرور{{ isset($formUser) && $formUser?->exists ? ' (اختياري)' : '' }} <span class="text-danger">{{ isset($formUser) && $formUser?->exists ? '' : '*' }}</span></label>
        <input type="password" name="password" class="form-control" {{ isset($formUser) && $formUser?->exists ? '' : 'required' }}>
        <small class="text-muted">{{ isset($formUser) && $formUser?->exists ? 'اترك الحقل فارغاً إذا كنت لا ترغب في تغيير كلمة المرور.' : 'الحد الأدنى 8 أحرف.' }}</small>
    </div>

    <div class="col-md-6">
        <label class="form-label">تأكيد كلمة المرور {{ isset($formUser) && $formUser?->exists ? '(اختياري)' : '' }}</label>
        <input type="password" name="password_confirmation" class="form-control" {{ isset($formUser) && $formUser?->exists ? '' : 'required' }}>
    </div>

    <div class="col-md-6">
        <label class="form-label">الدور <span class="text-danger">*</span></label>
        <select name="role" class="form-select" required>
            <option value="">اختر الدور</option>
            @foreach($roles as $value => $label)
                <option value="{{ $value }}" @selected(old('role', optional($formUser?->roles->first())->name) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">الصورة الشخصية</label>
        <input type="file" name="avatar" class="form-control" accept="image/*">
        <small class="text-muted">الحد الأقصى للحجم 2 ميجابايت.</small>
        @if($formUser?->avatar)
            <div class="mt-2 d-flex align-items-center gap-3">
                <img src="{{ $formUser->avatar_url }}" alt="صورة المستخدم" class="rounded-circle" width="48" height="48">
                <span class="text-muted">الصورة الحالية</span>
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">الحالة</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="user-active" name="active" value="1" @checked(old('active', $formUser?->active ?? true))>
            <label class="form-check-label" for="user-active">{{ old('active', $formUser?->active ?? true) ? 'نشط' : 'غير نشط' }}</label>
        </div>
    </div>
</div>
