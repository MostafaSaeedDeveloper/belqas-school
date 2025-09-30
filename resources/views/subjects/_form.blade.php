@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">كود المادة</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $subject->code) }}" required>
        @error('code')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">اسم المادة</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $subject->name) }}" required>
        @error('name')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">المرحلة الدراسية</label>
        <input type="text" name="grade_level" class="form-control" value="{{ old('grade_level', $subject->grade_level) }}" required>
        @error('grade_level')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">المعلم المسؤول</label>
        <select name="teacher_id" class="form-select">
            <option value="">—</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $subject->teacher_id) == $teacher->id)>{{ $teacher->name }}</option>
            @endforeach
        </select>
        @error('teacher_id')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">ساعات أسبوعية</label>
        <input type="number" name="weekly_hours" class="form-control" value="{{ old('weekly_hours', $subject->weekly_hours) }}">
        @error('weekly_hours')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">الوصف</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $subject->description) }}</textarea>
        @error('description')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mt-3 d-flex justify-content-between">
    <a href="{{ route('subjects.index') }}" class="btn btn-link">عودة</a>
    <button class="btn btn-success">حفظ</button>
</div>
