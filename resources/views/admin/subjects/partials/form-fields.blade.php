@php($subject = $subject ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="subject_name">اسم المادة</label>
        <input type="text" name="name" id="subject_name" class="form-control" required
               value="{{ old('name', $subject?->name) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="subject_code">الرمز</label>
        <input type="text" name="code" id="subject_code" class="form-control" required
               value="{{ old('code', $subject?->code) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="subject_teacher">المعلم المسؤول</label>
        <select name="teacher_id" id="subject_teacher" class="form-select">
            <option value="">-- لم يحدد --</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}"
                    @selected((string) old('teacher_id', $subject?->teacher_id) === (string) $teacher->id)>
                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="subject_classrooms">الفصول المرتبطة</label>
        <select name="classroom_ids[]" id="subject_classrooms" class="form-select" multiple size="4">
            @php($selectedClassrooms = collect(old('classroom_ids', $subject?->classrooms?->pluck('id')->all() ?? []))->map(fn($id) => (string) $id))
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}" @selected($selectedClassrooms->contains((string) $classroom->id))>
                    {{ $classroom->name }} - {{ $classroom->grade_level }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">اضغط مع الاستمرار لتحديد أكثر من فصل.</small>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold" for="subject_description">وصف المادة</label>
        <textarea name="description" id="subject_description" class="form-control" rows="3">{{ old('description', $subject?->description) }}</textarea>
    </div>
</div>
