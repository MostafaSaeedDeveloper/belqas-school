@php($classroom = $classroom ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="classroom_name">اسم الفصل</label>
        <input type="text" name="name" id="classroom_name" class="form-control" required
               value="{{ old('name', $classroom?->name) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="classroom_grade">المرحلة الدراسية</label>
        <input type="text" name="grade_level" id="classroom_grade" class="form-control" required
               value="{{ old('grade_level', $classroom?->grade_level) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="classroom_section">الشعبة</label>
        <input type="text" name="section" id="classroom_section" class="form-control"
               value="{{ old('section', $classroom?->section) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="classroom_room">رقم الغرفة</label>
        <input type="text" name="room_number" id="classroom_room" class="form-control"
               value="{{ old('room_number', $classroom?->room_number) }}">
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold" for="classroom_teacher">رائد الفصل</label>
        <select name="homeroom_teacher_id" id="classroom_teacher" class="form-select">
            <option value="">-- بدون رائد --</option>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}"
                    @selected((string) old('homeroom_teacher_id', $classroom?->homeroom_teacher_id) === (string) $teacher->id)>
                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
