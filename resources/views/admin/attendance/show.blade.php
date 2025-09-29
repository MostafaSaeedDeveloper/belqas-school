@extends('admin.layouts.master')

@section('title', 'تفاصيل سجل الحضور')

@section('page-header')
    @section('page-title', 'تفاصيل سجل الحضور')
    @section('page-subtitle', optional($attendance->attendance_date)->format('Y-m-d'))
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">الحضور</a></li>
        <li class="breadcrumb-item active">عرض</li>
    @endsection
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2 text-primary"></i>
                    بيانات الطالب
                </h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">الاسم</dt>
                    <dd class="col-7">{{ $attendance->enrollment->student->first_name }} {{ $attendance->enrollment->student->last_name }}</dd>

                    <dt class="col-5 text-muted">الفصل</dt>
                    <dd class="col-7">{{ $attendance->enrollment->classroom->name }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2 text-success"></i>
                    تفاصيل السجل
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted">التاريخ</div>
                            <div class="fw-semibold">{{ optional($attendance->attendance_date)->format('Y-m-d') ?: '—' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted">الحالة</div>
                            <div class="fw-semibold">
                                @switch($attendance->status)
                                    @case('present') حاضر @break
                                    @case('absent') غائب @break
                                    @case('late') متأخر @break
                                    @case('excused') مستأذن @break
                                    @default غير محدد
                                @endswitch
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="text-muted">آخر تحديث</div>
                            <div class="fw-semibold">{{ optional($attendance->updated_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">الملاحظات</label>
                        <div class="p-3 border rounded-3 bg-white">{{ $attendance->remarks ?: 'لا توجد ملاحظات.' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('attendance.index') }}" class="btn btn-outline-primary mt-4">
            <i class="fas fa-arrow-right"></i>
            الرجوع لسجل الحضور
        </a>
    </div>
</div>
@endsection
