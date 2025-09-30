@extends('admin.layouts.master')

@section('title', 'تعديل مادة دراسية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تعديل مادة دراسية')
    @section('page-subtitle', 'تحديث تفاصيل المادة والروابط المرتبطة بها')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">المواد الدراسية</a></li>
        <li class="breadcrumb-item"><a href="{{ route('subjects.show', $subject) }}">{{ $subject->name }}</a></li>
        <li class="breadcrumb-item active">تعديل</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('subjects.update', $subject) }}" method="POST">
                @csrf
                @method('PUT')

                @include('subjects._form')

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('subjects.show', $subject) }}" class="btn btn-outline-secondary">
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
