@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">{{ $teacher->name }}</h1>
            <div class="text-muted">{{ $teacher->employee_code }}</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-outline-primary">تعديل</a>
            <form method="post" action="{{ route('teachers.destroy', $teacher) }}" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                @csrf
                @method('delete')
                <button class="btn btn-outline-danger">حذف</button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">البيانات الأساسية</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">الاسم الإنجليزي</dt>
                        <dd class="col-sm-8">{{ $teacher->english_name ?? '—' }}</dd>
                        <dt class="col-sm-4">البريد الإلكتروني</dt>
                        <dd class="col-sm-8">{{ $teacher->email ?? '—' }}</dd>
                        <dt class="col-sm-4">الهاتف</dt>
                        <dd class="col-sm-8">{{ $teacher->phone ?? '—' }}</dd>
                        <dt class="col-sm-4">التخصص</dt>
                        <dd class="col-sm-8">{{ $teacher->specialization ?? '—' }}</dd>
                        <dt class="col-sm-4">الحالة</dt>
                        <dd class="col-sm-8">{{ $teacher->status_label }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">بيانات وظيفية</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">تاريخ التعيين</dt>
                        <dd class="col-sm-8">{{ optional($teacher->hire_date)->format('Y-m-d') ?? '—' }}</dd>
                        <dt class="col-sm-4">الراتب</dt>
                        <dd class="col-sm-8">{{ number_format($teacher->salary, 2) }} ر.س</dd>
                        <dt class="col-sm-4">ملاحظات</dt>
                        <dd class="col-sm-8">{{ $teacher->notes ?? '—' }}</dd>
                        <dt class="col-sm-4">آخر تحديث</dt>
                        <dd class="col-sm-8">{{ optional($teacher->updated_at)->diffForHumans() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('teachers.index') }}" class="btn btn-link">عودة للقائمة</a>
    </div>
</div>
@endsection
