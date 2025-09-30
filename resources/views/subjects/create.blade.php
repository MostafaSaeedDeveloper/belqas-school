@extends('admin.layouts.master')

@section('title', 'إضافة مادة دراسية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إضافة مادة دراسية')
    @section('page-subtitle', 'تحديد تفاصيل المادة وربطها بالمعلمين والفصول')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}">المواد الدراسية</a></li>
        <li class="breadcrumb-item active">إضافة مادة</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                @include('subjects._form')

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate-left"></i> إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ المادة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
