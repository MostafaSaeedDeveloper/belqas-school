@extends('admin.layouts.master')

@section('title', 'إضافة معلم جديد - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إضافة معلم جديد')
    @section('page-subtitle', 'تسجيل بيانات معلم في النظام')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}">المعلمين</a></li>
        <li class="breadcrumb-item active">إضافة معلم</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">بيانات المعلم</h5>
            <form method="POST" action="{{ route('teachers.store') }}" enctype="multipart/form-data">
                @csrf
                @include('teachers::partials.form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('teachers.index') }}" class="btn btn-light">إلغاء</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
