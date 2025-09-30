@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">{{ $class->name }}</h1>
            <div class="text-muted">{{ $class->grade_level }} @if($class->section) - {{ $class->section }} @endif</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('classes.edit', $class) }}" class="btn btn-outline-primary">تعديل</a>
            <form method="post" action="{{ route('classes.destroy', $class) }}" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                @csrf
                @method('delete')
                <button class="btn btn-outline-danger">حذف</button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">معلومات الفصل</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">السعة</dt>
                        <dd class="col-sm-8">{{ $class->capacity ?? '—' }}</dd>
                        <dt class="col-sm-4">رقم القاعة</dt>
                        <dd class="col-sm-8">{{ $class->room_number ?? '—' }}</dd>
                        <dt class="col-sm-4">رائد الفصل</dt>
                        <dd class="col-sm-8">{{ $class->homeroomTeacher?->name ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">ملاحظات</div>
                <div class="card-body">
                    {{ $class->notes ?? 'لا توجد ملاحظات' }}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('classes.index') }}" class="btn btn-link">عودة للقائمة</a>
    </div>
</div>
@endsection
