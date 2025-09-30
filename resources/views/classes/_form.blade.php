@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">اسم الفصل</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $class->name) }}" required>
        @error('name')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">المرحلة</label>
        <input type="text" name="grade_level" class="form-control" value="{{ old('grade_level', $class->grade_level) }}" required>
        @error('grade_level')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">الشعبة</label>
        <input type="text" name="section" class="form-control" value="{{ old('section', $class->section) }}">
        @error('section')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">السعة</label>
        <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $class->capacity) }}">
        @error('capacity')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">رائد الفصل</label>
        <select name="teacher_id" class="form-select">
            <option value="">—</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $class->teacher_id) == $teacher->id)>{{ $teacher->name }}</option>
            @endforeach
        </select>
        @error('teacher_id')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">رقم القاعة</label>
        <input type="text" name="room_number" class="form-control" value="{{ old('room_number', $class->room_number) }}">
        @error('room_number')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">ملاحظات</label>
        <textarea name="notes" rows="4" class="form-control">{{ old('notes', $class->notes) }}</textarea>
        @error('notes')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mt-3 d-flex justify-content-between">
    <a href="{{ route('classes.index') }}" class="btn btn-link">عودة</a>
    <button class="btn btn-success">حفظ</button>
</div>
