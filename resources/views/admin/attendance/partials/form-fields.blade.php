@php($record = $record ?? null)
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="attendance_enrollment">قيد الطالب</label>
        <select name="enrollment_id" id="attendance_enrollment" class="form-select" required>
            <option value="">-- اختر الطالب --</option>
            @foreach($enrollments as $enrollment)
                <option value="{{ $enrollment->id }}"
                    @selected((string) old('enrollment_id', $record?->enrollment_id) === (string) $enrollment->id)>
                    {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }} - {{ $enrollment->classroom->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="attendance_date">التاريخ</label>
        <input type="date" name="attendance_date" id="attendance_date" class="form-control" required
               value="{{ old('attendance_date', optional($record?->attendance_date)->toDateString() ?? now()->toDateString()) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="attendance_status">الحالة</label>
        <select name="status" id="attendance_status" class="form-select" required>
            @php($status = old('status', $record?->status))
            <option value="present" @selected($status === 'present')>حاضر</option>
            <option value="absent" @selected($status === 'absent')>غائب</option>
            <option value="late" @selected($status === 'late')>متأخر</option>
            <option value="excused" @selected($status === 'excused')>مستأذن</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold" for="attendance_remarks">ملاحظات</label>
        <textarea name="remarks" id="attendance_remarks" class="form-control" rows="3">{{ old('remarks', $record?->remarks) }}</textarea>
    </div>
</div>
