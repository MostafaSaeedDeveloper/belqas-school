@php($student = $student ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="first_name">الاسم الأول</label>
        <input type="text" name="first_name" id="first_name" class="form-control"
               value="{{ old('first_name', $student?->first_name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="last_name">اسم العائلة</label>
        <input type="text" name="last_name" id="last_name" class="form-control"
               value="{{ old('last_name', $student?->last_name) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold" for="gender">النوع</label>
        <select name="gender" id="gender" class="form-select">
            <option value="">-- اختر --</option>
            <option value="male" @selected(old('gender', $student?->gender) === 'male')>ذكر</option>
            <option value="female" @selected(old('gender', $student?->gender) === 'female')>أنثى</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold" for="birth_date">تاريخ الميلاد</label>
        <input type="date" name="birth_date" id="birth_date" class="form-control"
               value="{{ old('birth_date', optional($student?->birth_date)->toDateString()) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold" for="admission_date">تاريخ الالتحاق</label>
        <input type="date" name="admission_date" id="admission_date" class="form-control"
               value="{{ old('admission_date', optional($student?->admission_date)->toDateString()) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="classroom_id">الفصل الدراسي</label>
        <select name="classroom_id" id="classroom_id" class="form-select">
            <option value="">-- بدون فصل --</option>
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}"
                    @selected((string) old('classroom_id', $student?->classroom_id) === (string) $classroom->id)>
                    {{ $classroom->name }} - {{ $classroom->grade_level }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="guardian_name">اسم ولي الأمر</label>
        <input type="text" name="guardian_name" id="guardian_name" class="form-control"
               value="{{ old('guardian_name', $student?->guardian_name) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="guardian_phone">هاتف ولي الأمر</label>
        <input type="text" name="guardian_phone" id="guardian_phone" class="form-control"
               value="{{ old('guardian_phone', $student?->guardian_phone) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="address">العنوان</label>
        <input type="text" name="address" id="address" class="form-control"
               value="{{ old('address', $student?->address) }}">
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold" for="notes">ملاحظات</label>
        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $student?->notes) }}</textarea>
    </div>
</div>
