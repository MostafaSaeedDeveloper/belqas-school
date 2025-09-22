@extends('admin.layouts.master')

@section('title', 'ملف الطالب - ' . $student->name)

@section('page-header')
    @section('page-title', 'ملف الطالب')
    @section('page-subtitle', 'عرض بيانات الطالب ومتابعة نشاطه')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">{{ $student->name }}</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <img src="{{ $student->avatar_url }}" class="rounded-circle mb-3" width="120" height="120" alt="{{ $student->name }}">
                    <h4 class="fw-semibold">{{ $student->name }}</h4>
                    <span class="badge bg-primary-subtle text-primary mb-3">
                        {{ $student->roles->first()->display_name ?? $student->roles->first()->name ?? 'طالب' }}
                    </span>

                    <div class="d-flex flex-column gap-2 text-start">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-envelope text-primary"></i>
                            <span dir="ltr">{{ $student->email ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-user text-primary"></i>
                            <span dir="ltr">{{ $student->username }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-phone text-primary"></i>
                            <span dir="ltr">{{ $student->phone ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-toggle-on text-primary"></i>
                            <span>{{ $student->active ? 'حساب نشط' : 'حساب غير نشط' }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-start">
                        <p class="text-muted mb-1">آخر تسجيل دخول:</p>
                        <p class="fw-semibold">{{ $student->last_login_at ? $student->last_login_at->format('Y-m-d H:i') : 'لم يسجل بعد' }}</p>
                        <p class="text-muted mb-1">عنوان IP الأخير:</p>
                        <p class="fw-semibold" dir="ltr">{{ $student->last_login_ip ?? '—' }}</p>
                        <p class="text-muted mb-1">تاريخ الإنشاء:</p>
                        <p class="fw-semibold">{{ $student->created_at?->format('Y-m-d') ?? 'غير متوفر' }}</p>
                        <p class="text-muted mb-1">آخر تحديث:</p>
                        <p class="fw-semibold">{{ $student->updated_at?->diffForHumans() ?? 'غير متوفر' }}</p>
                    </div>

                    <div class="d-flex flex-column gap-2 mt-4">
                        @can('edit_students')
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> تعديل البيانات
                            </a>
                            <form action="{{ route('students.toggle-status', $student) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من تغيير حالة الطالب؟');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning">
                                    <i class="fas fa-user-shield"></i> {{ $student->active ? 'إيقاف التفعيل' : 'تفعيل الحساب' }}
                                </button>
                            </form>
                            <form action="{{ route('students.reset-password', $student) }}" method="POST" onsubmit="return confirm('سيتم إنشاء كلمة مرور جديدة للطالب، هل تريد المتابعة؟');">
                                @csrf
                                <button type="submit" class="btn btn-outline-info">
                                    <i class="fas fa-key"></i> إعادة تعيين كلمة المرور
                                </button>
                            </form>
                        @endcan
                        @can('delete_students')
                            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟ لا يمكن التراجع عن هذا الإجراء.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i> حذف الطالب
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-lock me-2"></i> الصلاحيات المرتبطة بالحساب</h5>
                    <span class="badge bg-secondary-subtle text-secondary">{{ $rolePermissions->count() }} صلاحية</span>
                </div>
                <div class="card-body">
                    @if($rolePermissions->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-lock-open fa-2x mb-3"></i>
                            <p class="mb-0">لا توجد صلاحيات إضافية مرتبطة بهذا الطالب.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($rolePermissions->chunk(ceil($rolePermissions->count() / 2)) as $column)
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        @foreach($column as $permission)
                                            <li class="list-group-item">
                                                <i class="fas fa-check-circle text-success me-2"></i>{{ $permission }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> إحصائيات الحساب</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-muted">عدد الأنشطة المسجلة</h6>
                                <p class="fw-bold mb-0">{{ $activityCount }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="text-muted">آخر تحديث للدور</h6>
                                <p class="fw-bold mb-0">{{ optional($student->roles->first()?->pivot)->created_at?->diffForHumans() ?? 'غير متوفر' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">ملاحظات إدارية</h6>
                                <p class="mb-0 text-muted">يمكنك إضافة نظام لملاحظات الأداء والسلوك في التحديثات القادمة.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
