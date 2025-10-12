@extends('admin.layouts.master')

@section('title', 'إضافة امتحان - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إضافة امتحان جديد')
    @section('page-subtitle', 'تهيئة بيانات الامتحان وتحديد مواعيده')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">الامتحانات</a></li>
        <li class="breadcrumb-item active">إضافة امتحان</li>
    @endsection
@endsection

@section('content')
    <form method="POST" action="{{ route('exams.store') }}" class="card shadow-sm">
        @csrf
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">عنوان الامتحان</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">الفصل الدراسي</label>
                    <select name="classroom_id" class="form-select" required>
                        <option value="">اختر الفصل</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">المادة الدراسية</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">اختر المادة</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">المراقب المسؤول</label>
                    <select name="supervisor_id" class="form-select">
                        <option value="">لم يحدد</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('supervisor_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">تاريخ الامتحان</label>
                    <input type="date" name="exam_date" class="form-control" value="{{ old('exam_date') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">وقت البداية</label>
                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">وقت النهاية</label>
                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">مكان الانعقاد</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="قاعة رقم 3">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">الفصل الدراسي/الترم</label>
                    <input type="text" name="term" class="form-control" value="{{ old('term') }}" placeholder="الفصل الأول">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">حالة الامتحان</label>
                    <select name="status" class="form-select" required>
                        <option value="scheduled" {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}>مجدول</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>منفذ</option>
                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>ملغى</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="تفاصيل إضافية حول الامتحان">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white text-end">
            <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary">إلغاء</a>
            <button class="btn btn-primary" type="submit"><i class="fas fa-save me-2"></i>حفظ الامتحان</button>
        </div>
    </form>
@endsection
