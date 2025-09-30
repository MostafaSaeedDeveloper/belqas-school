@php($editing = $subject->exists)
@php($selectedTeachers = collect(old('teacher_ids', $editing ? $subject->teachers->pluck('id')->toArray() : []))->map(fn($id) => (int) $id)->all())
@php($selectedClassrooms = collect(old('classroom_ids', $editing ? $subject->classrooms->pluck('id')->toArray() : []))->map(fn($id) => (int) $id)->all())

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">اسم المادة</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $subject->name) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">كود المادة</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $subject->code) }}" placeholder="مثال: MATH101">
    </div>
    <div class="col-md-3">
        <label class="form-label">الصف الدراسي</label>
        <select name="grade_level" class="form-select">
            <option value="">غير محدد</option>
            @foreach($gradeOptions as $grade)
                <option value="{{ $grade }}" @selected(old('grade_level', $subject->grade_level) === $grade)>{{ $grade }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">المعلمين المتخصصين</label>
        <select name="teacher_ids[]" class="form-select" multiple size="8">
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(in_array($teacher->id, $selectedTeachers, true))>
                    {{ $teacher->name }}
                    @if($teacher->teacherProfile?->specialization)
                        ({{ $teacher->teacherProfile->specialization }})
                    @endif
                </option>
            @endforeach
        </select>
        <small class="text-muted">سيتمكن المعلمون المحددون من متابعة المادة ضمن ملفاتهم الشخصية.</small>
    </div>

    <div class="col-md-6">
        <label class="form-label">الفصول المطبق بها المادة</label>
        <select name="classroom_ids[]" class="form-select" multiple size="8">
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}" @selected(in_array($classroom->id, $selectedClassrooms, true))>
                    {{ $classroom->name }}
                    @if($classroom->grade_level)
                        - {{ $classroom->grade_level }}
                    @endif
                </option>
            @endforeach
        </select>
        <small class="text-muted">يمكن لاحقاً تحديد المعلم المسؤول عن كل فصل من شاشة تكليف المواد.</small>
    </div>

    <div class="col-12">
        <label class="form-label">وصف المادة</label>
        <textarea name="description" rows="4" class="form-control" placeholder="أدخل وصف مختصر للمادة">{{ old('description', $subject->description) }}</textarea>
    </div>
</div>
