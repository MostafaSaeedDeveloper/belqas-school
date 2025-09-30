@php($editing = isset($teacher))

<div class="row g-3">
    <div class="col-md-5">
        <label class="form-label">اسم المعلم</label>
        <input type="text" name="name" value="{{ old('name', $editing ? $teacher->name : '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">اسم المستخدم</label>
        <input type="text" name="username" value="{{ old('username', $editing ? $teacher->username : '') }}" class="form-control" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">الرقم الوظيفي</label>
        <input type="text" name="teacher_code" value="{{ old('teacher_code', $editing ? optional($teacher->teacherProfile)->teacher_code : '') }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">حالة الحساب</label>
        <select name="active" class="form-select">
            <option value="1" @selected(old('active', $editing ? $teacher->active : true))>نشط</option>
            <option value="0" @selected(old('active', $editing ? ! $teacher->active : false))>موقوف</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" name="email" value="{{ old('email', $editing ? $teacher->email : '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">رقم الهاتف</label>
        <input type="text" name="phone" value="{{ old('phone', $editing ? $teacher->phone : '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">هاتف إضافي</label>
        <input type="text" name="phone_secondary" value="{{ old('phone_secondary', optional($teacher->teacherProfile ?? null)->phone_secondary) }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">كلمة المرور</label>
        <input type="password" name="password" class="form-control" @if(! $editing) required @endif placeholder="{{ $editing ? 'اتركها فارغة للحفاظ على الحالية' : '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">تأكيد كلمة المرور</label>
        <input type="password" name="password_confirmation" class="form-control" @if(! $editing) required @endif>
    </div>

    <div class="col-md-4">
        <label class="form-label">التخصص</label>
        <select name="specialization" class="form-select">
            <option value="">اختر التخصص</option>
            @foreach($specializations as $specialization)
                <option value="{{ $specialization }}" @selected(old('specialization', optional($teacher->teacherProfile ?? null)->specialization) === $specialization)>{{ $specialization }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">المؤهل العلمي</label>
        <input type="text" name="qualification" value="{{ old('qualification', optional($teacher->teacherProfile ?? null)->qualification) }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">النوع</label>
        <select name="gender" class="form-select">
            <option value="">غير محدد</option>
            <option value="male" @selected(old('gender', optional($teacher->teacherProfile ?? null)->gender) === 'male')>ذكر</option>
            <option value="female" @selected(old('gender', optional($teacher->teacherProfile ?? null)->gender) === 'female')>أنثى</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">سنوات الخبرة</label>
        <input type="number" name="experience_years" value="{{ old('experience_years', optional($teacher->teacherProfile ?? null)->experience_years) }}" class="form-control" min="0" max="60">
    </div>

    <div class="col-md-3">
        <label class="form-label">تاريخ التعيين</label>
        <input type="date" name="hire_date" value="{{ old('hire_date', optional(optional($teacher->teacherProfile ?? null)->hire_date)->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-5">
        <label class="form-label">المواد الدراسية</label>
        <input type="text" name="subjects" value="{{ old('subjects', collect(optional($teacher->teacherProfile ?? null)->subjects)->implode(', ')) }}" class="form-control" placeholder="اكتب المواد مفصولة بفاصلة">
    </div>
    <div class="col-md-4">
        <label class="form-label">ساعات التواجد</label>
        <input type="text" name="office_hours" value="{{ old('office_hours', optional($teacher->teacherProfile ?? null)->office_hours) }}" class="form-control" placeholder="مثال: الأحد-الخميس 8ص-2م">
    </div>

    <div class="col-md-8">
        <label class="form-label">العنوان</label>
        <input type="text" name="address" value="{{ old('address', optional($teacher->teacherProfile ?? null)->address) }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">صورة المعلم</label>
        <input type="file" name="avatar" class="form-control">
    </div>

    <div class="col-12">
        <label class="form-label">ملاحظات</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', optional($teacher->teacherProfile ?? null)->notes) }}</textarea>
    </div>
</div>
