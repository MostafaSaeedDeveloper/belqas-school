@php($editing = isset($student))

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">اسم الطالب</label>
        <input type="text" name="name" value="{{ old('name', $editing ? $student->name : '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">اسم المستخدم</label>
        <input type="text" name="username" value="{{ old('username', $editing ? $student->username : '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">الرقم التعريفي</label>
        <input type="text" name="student_code" value="{{ old('student_code', $editing ? optional($student->studentProfile)->student_code : '') }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" name="email" value="{{ old('email', $editing ? $student->email : '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">رقم الهاتف</label>
        <input type="text" name="phone" value="{{ old('phone', $editing ? $student->phone : '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">حالة الحساب</label>
        <select name="active" class="form-select">
            <option value="1" @selected(old('active', $editing ? $student->active : true))>نشط</option>
            <option value="0" @selected(old('active', $editing ? ! $student->active : false))>موقوف</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">كلمة المرور</label>
        <input type="password" name="password" class="form-control" @if(! $editing) required @endif placeholder="{{ $editing ? 'اتركها فارغة للحفاظ على الحالية' : '' }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">تأكيد كلمة المرور</label>
        <input type="password" name="password_confirmation" class="form-control" @if(! $editing) required @endif>
    </div>

    <div class="col-md-3">
        <label class="form-label">الصف الدراسي</label>
        <select name="grade_level" class="form-select">
            <option value="">اختر الصف</option>
            @foreach($gradeOptions as $grade)
                <option value="{{ $grade }}" @selected(old('grade_level', optional($student->studentProfile ?? null)->grade_level) === $grade)>{{ $grade }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">الفصل</label>
        <select name="classroom" class="form-select">
            <option value="">اختر الفصل</option>
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom }}" @selected(old('classroom', optional($student->studentProfile ?? null)->classroom) === $classroom)>{{ $classroom }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">الجنس</label>
        <select name="gender" class="form-select">
            <option value="">غير محدد</option>
            <option value="male" @selected(old('gender', optional($student->studentProfile ?? null)->gender) === 'male')>ذكر</option>
            <option value="female" @selected(old('gender', optional($student->studentProfile ?? null)->gender) === 'female')>أنثى</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">تاريخ الميلاد</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional(optional($student->studentProfile ?? null)->date_of_birth)->format('Y-m-d')) }}" class="form-control">
    </div>

    <div class="col-md-4">
        <label class="form-label">تاريخ الالتحاق</label>
        <input type="date" name="enrollment_date" value="{{ old('enrollment_date', optional(optional($student->studentProfile ?? null)->enrollment_date)->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">اسم ولي الأمر</label>
        <input type="text" name="guardian_name" value="{{ old('guardian_name', optional($student->studentProfile ?? null)->guardian_name) }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">هاتف ولي الأمر</label>
        <input type="text" name="guardian_phone" value="{{ old('guardian_phone', optional($student->studentProfile ?? null)->guardian_phone) }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">العنوان</label>
        <input type="text" name="address" value="{{ old('address', optional($student->studentProfile ?? null)->address) }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">صورة الطالب</label>
        <input type="file" name="avatar" class="form-control">
    </div>

    <div class="col-12">
        <label class="form-label">ملاحظات إضافية</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', optional($student->studentProfile ?? null)->notes) }}</textarea>
    </div>
</div>
