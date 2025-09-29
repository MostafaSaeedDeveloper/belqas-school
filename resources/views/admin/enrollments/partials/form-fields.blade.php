@php($enrollment = $enrollment ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="enrollment_student">الطالب</label>
        <select name="student_id" id="enrollment_student" class="form-select" required>
            <option value="">-- اختر الطالب --</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}"
                    @selected((string) old('student_id', $enrollment?->student_id) === (string) $student->id)>
                    {{ $student->first_name }} {{ $student->last_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="enrollment_classroom">الفصل</label>
        <select name="classroom_id" id="enrollment_classroom" class="form-select" required>
            <option value="">-- اختر الفصل --</option>
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}"
                    @selected((string) old('classroom_id', $enrollment?->classroom_id) === (string) $classroom->id)>
                    {{ $classroom->name }} - {{ $classroom->grade_level }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="enrollment_date">تاريخ القيد</label>
        <input type="date" name="enrolled_at" id="enrollment_date" class="form-control"
               value="{{ old('enrolled_at', optional($enrollment?->enrolled_at)->toDateString() ?? now()->toDateString()) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="enrollment_active">حالة القيد</label>
        <select name="active" id="enrollment_active" class="form-select">
            @php($active = old('active', $enrollment?->active ?? true))
            <option value="1" @selected($active) >نشط</option>
            <option value="0" @selected(!$active)>غير نشط</option>
        </select>
    </div>
</div>
