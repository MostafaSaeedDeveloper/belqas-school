@php($grade = $grade ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="grade_enrollment">قيد الطالب</label>
        <select name="enrollment_id" id="grade_enrollment" class="form-select" required>
            <option value="">-- اختر الطالب والفصل --</option>
            @foreach($enrollments as $enrollment)
                <option value="{{ $enrollment->id }}"
                    @selected((string) old('enrollment_id', $grade?->enrollment_id) === (string) $enrollment->id)>
                    {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }} - {{ $enrollment->classroom->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="grade_assessment">التقييم</label>
        <select name="assessment_id" id="grade_assessment" class="form-select" required>
            <option value="">-- اختر التقييم --</option>
            @foreach($assessments as $assessmentOption)
                <option value="{{ $assessmentOption->id }}"
                    @selected((string) old('assessment_id', $grade?->assessment_id) === (string) $assessmentOption->id)>
                    {{ $assessmentOption->name }} ({{ $assessmentOption->subject->name }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold" for="grade_score">الدرجة</label>
        <input type="number" name="score" id="grade_score" class="form-control" min="0" required
               value="{{ old('score', $grade?->score) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold" for="grade_date">تاريخ الرصد</label>
        <input type="date" name="graded_at" id="grade_date" class="form-control"
               value="{{ old('graded_at', optional($grade?->graded_at)->toDateString()) }}">
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold" for="grade_remarks">ملاحظات</label>
        <textarea name="remarks" id="grade_remarks" class="form-control" rows="3">{{ old('remarks', $grade?->remarks) }}</textarea>
    </div>
</div>
