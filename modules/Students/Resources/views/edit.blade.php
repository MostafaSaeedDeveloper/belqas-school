@extends('admin.layouts.master')

@section('title', 'تعديل بيانات الطالب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تعديل بيانات الطالب')
    @section('page-subtitle', 'تحديث معلومات الطالب وبيانات ولي الأمر')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">{{ $student->name }}</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">بيانات الطالب</h5>
            <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('students::partials.form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('students.show', $student) }}" class="btn btn-light">إلغاء</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> تحديث البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
