@extends('admin.layouts.master')

@section('title', 'تعديل بيانات الطالب')
@section('page-title', 'تعديل بيانات الطالب')
@section('page-subtitle', 'قم بتحديث بيانات الطالب المحفوظة في النظام')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
    <li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->display_name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">تعديل</li>
@endsection

@section('content')
    <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        @include('students.partials.form')
    </form>

    @can('delete_students')
        <div class="card mt-4">
            <div class="card-body">
                <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب بشكل نهائي؟');">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 text-danger">حذف ملف الطالب</h5>
                            <p class="mb-0 text-muted">سيتم حذف جميع بيانات الطالب من النظام ولا يمكن استعادتها.</p>
                        </div>
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash-alt ms-1"></i>
                            حذف الطالب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
@endsection
