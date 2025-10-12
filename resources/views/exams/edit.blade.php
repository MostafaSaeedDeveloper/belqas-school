@extends('admin.layouts.master')

@section('title', 'تعديل الامتحان - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تعديل الامتحان')
    @section('page-subtitle', 'تحديث بيانات الامتحان وجدوله الزمني')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">الامتحانات</a></li>
        <li class="breadcrumb-item active">تعديل الامتحان</li>
    @endsection
@endsection

@section('content')
    <form method="POST" action="{{ route('exams.update', $exam) }}" class="card shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">عنوان الامتحان</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $exam->title) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">الفصل الدراسي</label>
                    <select name="classroom_id" class="form-select" required>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ old('classroom_id', $exam->classroom_id) == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">المادة الدراسية</label>
                    <select name="subject_id" class="form-select" required>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">المراقب المسؤول</label>
                    <select name="supervisor_id" class="form-select">
                        <option value="">لم يحدد</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('supervisor_id', $exam->supervisor_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">تاريخ الامتحان</label>
                    <input type="date" name="exam_date" class="form-control" value="{{ old('exam_date', $exam->exam_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">وقت البداية</label>
                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time', optional($exam->start_time)->format('H:i')) }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">وقت النهاية</label>
                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time', optional($exam->end_time)->format('H:i')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">مكان الانعقاد</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $exam->location) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">الفصل الدراسي/الترم</label>
                    <input type="text" name="term" class="form-control" value="{{ old('term', $exam->term) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">حالة الامتحان</label>
                    <select name="status" class="form-select" required>
                        <option value="scheduled" {{ old('status', $exam->status) === 'scheduled' ? 'selected' : '' }}>مجدول</option>
                        <option value="completed" {{ old('status', $exam->status) === 'completed' ? 'selected' : '' }}>منفذ</option>
                        <option value="cancelled" {{ old('status', $exam->status) === 'cancelled' ? 'selected' : '' }}>ملغى</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes', $exam->notes) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <a href="{{ route('exams.show', $exam) }}" class="btn btn-outline-secondary">عرض التفاصيل</a>
            <div>
                <a href="{{ route('exams.index') }}" class="btn btn-light">إلغاء</a>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save me-2"></i>تحديث الامتحان</button>
            </div>
        </div>
    </form>
@endsection
