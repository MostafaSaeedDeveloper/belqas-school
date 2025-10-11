@extends('admin.layouts.master')

@section('title', 'الإعدادات العامة - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الإعدادات العامة')
    @section('page-subtitle', 'تهيئة البيانات الرئيسية والهوية المؤسسية للنظام')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الإعدادات</li>
    @endsection
@endsection

@section('content')
    <form method="POST" action="{{ route('settings.general.update') }}" class="card shadow-sm">
        @csrf
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">اسم المدرسة</label>
                    <input type="text" name="school_name" value="{{ old('school_name', $settings['school_name']) }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">العام الدراسي</label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', $settings['academic_year']) }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">وقت إغلاق الحضور</label>
                    <input type="time" name="attendance_cutoff" value="{{ old('attendance_cutoff', $settings['attendance_cutoff']) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">البريد الإلكتروني الرسمي</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="form-control" placeholder="info@school.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">رقم التواصل</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="form-control" placeholder="01000000000">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">عنوان المدرسة</label>
                    <input type="text" name="school_address" value="{{ old('school_address', $settings['school_address']) }}" class="form-control">
                </div>
            </div>
        </div>
        <div class="card-footer bg-white text-end">
            <button class="btn btn-primary" type="submit"><i class="fas fa-save me-2"></i>حفظ الإعدادات</button>
        </div>
    </form>
@endsection
