@php($assessment = $assessment ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="assessment_subject">المادة</label>
        <select name="subject_id" id="assessment_subject" class="form-select" required>
            <option value="">-- اختر المادة --</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}"
                    @selected((string) old('subject_id', $assessment?->subject_id) === (string) $subject->id)>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="assessment_classroom">الفصل</label>
        <select name="classroom_id" id="assessment_classroom" class="form-select" required>
            <option value="">-- اختر الفصل --</option>
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}"
                    @selected((string) old('classroom_id', $assessment?->classroom_id) === (string) $classroom->id)>
                    {{ $classroom->name }} - {{ $classroom->grade_level }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="assessment_name">اسم التقييم</label>
        <input type="text" name="name" id="assessment_name" class="form-control" required
               value="{{ old('name', $assessment?->name) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold" for="assessment_date">تاريخ التقييم</label>
        <input type="date" name="assessment_date" id="assessment_date" class="form-control" required
               value="{{ old('assessment_date', optional($assessment?->assessment_date)->toDateString()) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold" for="assessment_max">الدرجة العظمى</label>
        <input type="number" name="max_score" id="assessment_max" min="1" class="form-control" required
               value="{{ old('max_score', $assessment?->max_score) }}">
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold" for="assessment_description">وصف إضافي</label>
        <textarea name="description" id="assessment_description" class="form-control" rows="3">{{ old('description', $assessment?->description) }}</textarea>
    </div>
</div>
