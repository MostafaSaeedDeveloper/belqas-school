@extends('admin.layouts.master')

@section('title', 'تعديل بيانات المستخدم - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تعديل بيانات المستخدم')
    @section('page-subtitle', 'تحديث بيانات الحساب والصلاحيات')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">المستخدمون</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></li>
        <li class="breadcrumb-item active">تعديل</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i> تعديل بيانات {{ $user->name }}</h5>
            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-right"></i> عرض الملف
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                @include('users::partials.form', ['roles' => $roles, 'user' => $user])

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-light">
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> تحديث البيانات
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
