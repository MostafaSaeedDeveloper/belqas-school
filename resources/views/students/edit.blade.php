@extends('admin.layouts.master')

@section('title', 'تعديل بيانات الطالب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تعديل بيانات الطالب')
    @section('page-subtitle', 'تحديث بيانات الطالب والسجلات المرتبطة به')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->name }}</a></li>
        <li class="breadcrumb-item active">تعديل</li>
    @endsection
@endsection

@section('content')
    <form action="{{ route('students.update', $student) }}" method="POST" class="card shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">اسم الطالب <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">الاسم بالإنجليزية</label>
                    <input type="text" name="english_name" value="{{ old('english_name', $student->english_name) }}" class="form-control @error('english_name') is-invalid @enderror">
                    @error('english_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">كود الطالب</label>
                    <input type="text" name="student_code" value="{{ old('student_code', $student->student_code) }}" class="form-control @error('student_code') is-invalid @enderror">
                    @error('student_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">الجنس</label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="">حدد الجنس</option>
                        @foreach($genderOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('gender', $student->gender) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">تاريخ الميلاد</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', optional($student->birth_date)->format('Y-m-d')) }}" class="form-control @error('birth_date') is-invalid @enderror">
                    @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">الرقم القومي / هوية الطالب</label>
                    <input type="text" name="national_id" value="{{ old('national_id', $student->national_id) }}" class="form-control @error('national_id') is-invalid @enderror">
                    @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">الصف الدراسي <span class="text-danger">*</span></label>
                    <input type="text" name="grade_level" value="{{ old('grade_level', $student->grade_level) }}" class="form-control @error('grade_level') is-invalid @enderror" required>
                    @error('grade_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">الفصل</label>
                    <input type="text" name="classroom" value="{{ old('classroom', $student->classroom) }}" class="form-control @error('classroom') is-invalid @enderror">
                    @error('classroom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">تاريخ القبول</label>
                    <input type="date" name="enrollment_date" value="{{ old('enrollment_date', optional($student->enrollment_date)->format('Y-m-d')) }}" class="form-control @error('enrollment_date') is-invalid @enderror">
                    @error('enrollment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">الحالة <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $student->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">رقم الجوال</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">اسم ولي الأمر</label>
                    <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" class="form-control @error('guardian_name') is-invalid @enderror">
                    @error('guardian_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">صلة القرابة</label>
                    <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $student->guardian_relationship) }}" class="form-control @error('guardian_relationship') is-invalid @enderror">
                    @error('guardian_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">هاتف ولي الأمر</label>
                    <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}" class="form-control @error('guardian_phone') is-invalid @enderror">
                    @error('guardian_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">العنوان</label>
                    <input type="text" name="address" value="{{ old('address', $student->address) }}" class="form-control @error('address') is-invalid @enderror">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">المدينة / المركز</label>
                    <input type="text" name="city" value="{{ old('city', $student->city) }}" class="form-control @error('city') is-invalid @enderror">
                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">وسيلة المواصلات</label>
                    <input type="text" name="transportation_method" value="{{ old('transportation_method', $student->transportation_method) }}" class="form-control @error('transportation_method') is-invalid @enderror">
                    @error('transportation_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">المستحقات المالية</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" name="outstanding_fees" value="{{ old('outstanding_fees', $student->outstanding_fees) }}" class="form-control @error('outstanding_fees') is-invalid @enderror">
                        <span class="input-group-text">ج.م</span>
                        @error('outstanding_fees')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">ملاحظات طبية</label>
                    <textarea name="medical_notes" rows="3" class="form-control @error('medical_notes') is-invalid @enderror">{{ old('medical_notes', $student->medical_notes) }}</textarea>
                    @error('medical_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">ملاحظات إضافية</label>
                    <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $student->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <a href="{{ route('students.show', $student) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right"></i> إلغاء والعودة
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> تحديث البيانات
            </button>
        </div>
    </form>
@endsection
