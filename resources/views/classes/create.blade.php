@extends('admin.layouts.master')

@section('title', 'إضافة فصل دراسي جديد')
@section('page-title', 'إضافة فصل دراسي')
@section('page-subtitle', 'إنشاء فصل جديد وربط الشعب الخاصة به')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الفصول الدراسية</a></li>
    <li class="breadcrumb-item active" aria-current="page">إضافة فصل</li>
@endsection

@section('content')
    <form action="{{ route('classes.store') }}" method="POST">
        @csrf

        @include('classes.partials.form')

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('classes.index') }}" class="btn btn-light">إلغاء</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save ms-1"></i>
                حفظ الفصل
            </button>
        </div>
    </form>
@endsection
