@extends('admin.layouts.master')

@section('title', 'إضافة طالب جديد - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إضافة طالب جديد')
    @section('page-subtitle', 'تعبئة بيانات الطالب وتفعيل حسابه')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">إضافة طالب</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i> بيانات الطالب</h5>
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-right"></i> العودة للقائمة
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @include('students::partials.form', ['student' => new \App\Models\User()])

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('students.index') }}" class="btn btn-light">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ الطالب
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('inline-scripts')
<script>
    (function () {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endpush
