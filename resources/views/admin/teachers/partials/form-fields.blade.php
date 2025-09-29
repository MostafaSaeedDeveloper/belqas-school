@php($teacher = $teacher ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="teacher_first_name">الاسم الأول</label>
        <input type="text" name="first_name" id="teacher_first_name" class="form-control" required
               value="{{ old('first_name', $teacher?->first_name) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="teacher_last_name">اسم العائلة</label>
        <input type="text" name="last_name" id="teacher_last_name" class="form-control" required
               value="{{ old('last_name', $teacher?->last_name) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="teacher_email">البريد الإلكتروني</label>
        <input type="email" name="email" id="teacher_email" class="form-control" required
               value="{{ old('email', $teacher?->email) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="teacher_phone">رقم الهاتف</label>
        <input type="text" name="phone" id="teacher_phone" class="form-control"
               value="{{ old('phone', $teacher?->phone) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="teacher_hire_date">تاريخ التعيين</label>
        <input type="date" name="hire_date" id="teacher_hire_date" class="form-control"
               value="{{ old('hire_date', optional($teacher?->hire_date)->toDateString()) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="teacher_specialization">التخصص</label>
        <input type="text" name="specialization" id="teacher_specialization" class="form-control"
               value="{{ old('specialization', $teacher?->specialization) }}">
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold" for="teacher_notes">ملاحظات</label>
        <textarea name="notes" id="teacher_notes" class="form-control" rows="3">{{ old('notes', $teacher?->notes) }}</textarea>
    </div>
</div>
