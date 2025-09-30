@php($editing = $classroom->exists)
@php($selectedTeachers = collect(old('teacher_ids', $editing ? $classroom->teachers->pluck('id')->toArray() : []))->map(fn($id) => (int) $id)->all())
@php($selectedStudents = collect(old('student_ids', $editing ? $classroom->students->pluck('id')->toArray() : []))->map(fn($id) => (int) $id)->all())
@php($selectedSubjects = collect(old('subject_ids', $editing ? $classroom->subjects->pluck('id')->toArray() : []))->map(fn($id) => (int) $id)->all())

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">اسم الفصل</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $classroom->name) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">الصف الدراسي</label>
        <select name="grade_level" class="form-select" required>
            <option value="">اختر الصف</option>
            @foreach($gradeOptions as $grade)
                <option value="{{ $grade }}" @selected(old('grade_level', $classroom->grade_level) === $grade)>{{ $grade }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">الشعبة</label>
        <input type="text" name="section" class="form-control" value="{{ old('section', $classroom->section) }}" placeholder="مثال: أ">
    </div>
    <div class="col-md-2">
        <label class="form-label">السعة الاستيعابية</label>
        <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $classroom->capacity) }}" min="0">
    </div>

    <div class="col-md-4">
        <label class="form-label">رائد الفصل</label>
        <select name="homeroom_teacher_id" class="form-select">
            <option value="">اختر المعلم</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id', $classroom->homeroom_teacher_id) == $teacher->id)>
                    {{ $teacher->name }}
                    @if($teacher->teacherProfile?->specialization)
                        - {{ $teacher->teacherProfile->specialization }}
                    @endif
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">المعلمين المرتبطين بالفصل</label>
        <select name="teacher_ids[]" class="form-select" multiple size="6">
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(in_array($teacher->id, $selectedTeachers, true))>
                    {{ $teacher->name }}
                    @if($teacher->teacherProfile?->specialization)
                        ({{ $teacher->teacherProfile->specialization }})
                    @endif
                </option>
            @endforeach
        </select>
        <small class="text-muted">يمكن اختيار أكثر من معلم بالضغط على Ctrl أو Shift.</small>
    </div>
    <div class="col-md-4">
        <label class="form-label">المواد الدراسية في الفصل</label>
        <select name="subject_ids[]" class="form-select" multiple size="6">
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" @selected(in_array($subject->id, $selectedSubjects, true))>
                    {{ $subject->name }}
                    @if($subject->grade_level)
                        ({{ $subject->grade_level }})
                    @endif
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">الطلاب المسجلين</label>
        <select name="student_ids[]" class="form-select" multiple size="10">
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected(in_array($student->id, $selectedStudents, true))>
                    {{ $student->name }}
                    @if($student->studentProfile?->grade_level)
                        - {{ $student->studentProfile->grade_level }}
                    @endif
                </option>
            @endforeach
        </select>
        <small class="text-muted">يتم تحديث بطاقة الطالب تلقائياً عند ربطه أو إزالته من الفصل.</small>
    </div>
    <div class="col-md-6">
        <label class="form-label">وصف الفصل</label>
        <textarea name="description" rows="8" class="form-control" placeholder="أدخل أي ملاحظات تنظيمية حول الفصل">{{ old('description', $classroom->description) }}</textarea>
    </div>
</div>
