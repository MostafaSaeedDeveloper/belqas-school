@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">{{ $subject->name }}</h1>
            <div class="text-muted">{{ $subject->code }} • {{ $subject->grade_level }}</div>
        </div>
        <div class="btn-group">
            <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-outline-primary">تعديل</a>
            <form method="post" action="{{ route('subjects.destroy', $subject) }}" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                @csrf
                @method('delete')
                <button class="btn btn-outline-danger">حذف</button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">معلومات المادة</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">المعلم</dt>
                        <dd class="col-sm-8">{{ $subject->teacher?->name ?? '—' }}</dd>
                        <dt class="col-sm-4">الساعات الأسبوعية</dt>
                        <dd class="col-sm-8">{{ $subject->weekly_hours ?? '—' }}</dd>
                        <dt class="col-sm-4">آخر تحديث</dt>
                        <dd class="col-sm-8">{{ optional($subject->updated_at)->diffForHumans() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">الوصف</div>
                <div class="card-body">
                    {{ $subject->description ?? 'لا يوجد وصف' }}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('subjects.index') }}" class="btn btn-link">عودة للقائمة</a>
    </div>
</div>
@endsection
