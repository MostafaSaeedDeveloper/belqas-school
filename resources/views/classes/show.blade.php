@extends('admin.layouts.master')

@section('title', 'تفاصيل الفصل: ' . $class->name)
@section('page-title', 'تفاصيل الفصل الدراسي')
@section('page-subtitle', 'عرض البيانات العامة والشعب المرتبطة بالفصل')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الفصول الدراسية</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $class->name }}</li>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">بيانات الفصل الأساسية</h5>
                    <div class="btn-group">
                        @can('edit_classes')
                            <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit ms-1"></i> تعديل
                            </a>
                        @endcan
                        <a href="{{ route('classes.timetables', ['class_id' => $class->id]) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-calendar-alt ms-1"></i> جدول الحصص
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="text-muted">اسم الفصل</span>
                                <h6 class="mb-0">{{ $class->name }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="text-muted">رمز الفصل</span>
                                <h6 class="mb-0">{{ $class->code ?? '—' }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="text-muted">الصف الدراسي</span>
                                <h6 class="mb-0">{{ optional($class->grade)->name ?? 'غير محدد' }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="text-muted">السعة القصوى</span>
                                <h6 class="mb-0">{{ $class->capacity ? $class->capacity . ' طالب' : 'غير محددة' }}</h6>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <span class="text-muted">وصف الفصل</span>
                                <p class="mb-0">{{ $class->description ?: 'لا توجد ملاحظات مسجلة لهذا الفصل.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between text-muted small">
                    <span>تاريخ الإنشاء: {{ optional($class->created_at)->format('Y/m/d H:i') }}</span>
                    <span>آخر تحديث: {{ optional($class->updated_at)->diffForHumans() }}</span>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">الشعب المسجلة</h5>
                    @can('edit_classes')
                        <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus ms-1"></i> إدارة الشعب
                        </a>
                    @endcan
                </div>
                <div class="card-body p-0">
                    @if($class->sections->isNotEmpty())
                        <div class="list-group list-group-flush">
                            @foreach($class->sections as $section)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $section->name }}</h6>
                                            <div class="text-muted small">
                                                @if($section->code)
                                                    <span class="me-3"><i class="fas fa-tag ms-1"></i> {{ $section->code }}</span>
                                                @endif
                                                <span><i class="fas fa-calendar ms-1"></i> {{ $section->timetables_count }} حصة مجدولة</span>
                                            </div>
                                            @if($section->description)
                                                <p class="mb-0 text-muted small mt-2">{{ $section->description }}</p>
                                            @endif
                                        </div>
                                        <a href="{{ route('classes.timetables', ['class_id' => $class->id]) }}" class="btn btn-sm btn-light">عرض الجدول</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            لا توجد شعب مسجلة لهذا الفصل بعد. يمكنك إضافتها من خلال صفحة التعديل.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">إحصائيات سريعة</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">عدد الشعب</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $class->sections->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">السعة القصوى</span>
                        <span>{{ $class->capacity ? $class->capacity . ' طالب' : 'غير محدد' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">آخر تعديل</span>
                        <span>{{ optional($class->updated_at)->format('Y/m/d H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">إجراءات سريعة</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('classes.timetables', ['class_id' => $class->id]) }}" class="btn btn-outline-info">
                        <i class="fas fa-calendar-alt ms-1"></i> إدارة الجدول الدراسي
                    </a>
                    <a href="{{ route('students.index', ['class_id' => $class->id]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-users ms-1"></i> استعراض طلاب الفصل
                    </a>
                    @can('delete_classes')
                        <form action="{{ route('classes.destroy', $class) }}" method="POST" onsubmit="return confirm('هل ترغب في حذف هذا الفصل وجميع الشعب المرتبطة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash ms-1"></i> حذف الفصل
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
