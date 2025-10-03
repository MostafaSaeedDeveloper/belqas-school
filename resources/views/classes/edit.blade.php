@extends('admin.layouts.master')

@section('title', 'تعديل بيانات الفصل - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تعديل بيانات الفصل')
    @section('page-subtitle', 'تحديث بيانات الطلاب والمعلمين المرتبطين')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الفصول الدراسية</a></li>
        <li class="breadcrumb-item"><a href="{{ route('classes.show', $classroom) }}">{{ $classroom->name }}</a></li>
        <li class="breadcrumb-item active">تعديل</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('classes.update', $classroom) }}" method="POST">
                @csrf
                @method('PUT')

                @include('classes._form')

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('classes.show', $classroom) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate-left"></i> إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> تحديث البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
