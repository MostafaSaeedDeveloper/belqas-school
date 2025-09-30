@extends('admin.layouts.master')

@section('title', 'إضافة فصل دراسي - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إضافة فصل دراسي جديد')
    @section('page-subtitle', 'تحديد بيانات الفصل وربط الطلاب والمعلمين')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الفصول الدراسية</a></li>
        <li class="breadcrumb-item active">إضافة فصل</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('classes.store') }}" method="POST">
                @csrf
                @include('classes._form')

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate-left"></i> إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ الفصل
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
