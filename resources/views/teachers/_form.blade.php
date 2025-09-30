@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">كود الموظف</label>
        <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code', $teacher->employee_code) }}">
        @error('employee_code')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">الاسم بالعربية</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $teacher->name) }}" required>
        @error('name')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">الاسم بالإنجليزية</label>
        <input type="text" name="english_name" class="form-control" value="{{ old('english_name', $teacher->english_name) }}">
        @error('english_name')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $teacher->email) }}">
        @error('email')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">رقم الجوال</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $teacher->phone) }}">
        @error('phone')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">التخصص</label>
        <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $teacher->specialization) }}">
        @error('specialization')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">تاريخ التعيين</label>
        <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date', optional($teacher->hire_date)->format('Y-m-d')) }}">
        @error('hire_date')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">الراتب الشهري</label>
        <input type="number" step="0.01" name="salary" class="form-control" value="{{ old('salary', $teacher->salary) }}">
        @error('salary')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">الحالة</label>
        <select name="status" class="form-select">
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $teacher->status) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">ملاحظات</label>
        <textarea name="notes" rows="4" class="form-control">{{ old('notes', $teacher->notes) }}</textarea>
        @error('notes')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mt-3 d-flex justify-content-between">
    <a href="{{ route('teachers.index') }}" class="btn btn-link">عودة</a>
    <button class="btn btn-success">حفظ</button>
</div>
