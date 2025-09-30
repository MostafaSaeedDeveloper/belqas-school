@extends('admin.layouts.master')

@section('title', 'تفاصيل المستخدم - ' . $user->name)

@section('page-header')
    @section('page-title', 'تفاصيل المستخدم')
    @section('page-subtitle', 'عرض بيانات المستخدم وتحليل نشاطه')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">المستخدمون</a></li>
        <li class="breadcrumb-item active">{{ $user->name }}</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <img src="{{ $user->avatar_url }}" class="rounded-circle mb-3" width="120" height="120" alt="{{ $user->name }}">
                    <h4 class="fw-semibold">{{ $user->name }}</h4>
                    <span class="badge bg-primary-subtle text-primary mb-3">
                        {{ $user->roles->first()->display_name ?? $user->roles->first()->name ?? 'بدون دور' }}
                    </span>

                    <div class="d-flex flex-column gap-2 text-start">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-envelope text-primary"></i>
                            <span dir="ltr">{{ $user->email ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-user text-primary"></i>
                            <span dir="ltr">{{ $user->username }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-phone text-primary"></i>
                            <span dir="ltr">{{ $user->phone ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-toggle-on text-primary"></i>
                            <span>{{ $user->active ? 'حساب نشط' : 'حساب غير نشط' }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-start">
                        <p class="text-muted mb-1">آخر تسجيل دخول:</p>
                        <p class="fw-semibold">{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'لم يسجل بعد' }}</p>
                        <p class="text-muted mb-1">عنوان IP الأخير:</p>
                        <p class="fw-semibold" dir="ltr">{{ $user->last_login_ip ?? '—' }}</p>
                        <p class="text-muted mb-1">تاريخ الإنشاء:</p>
                        <p class="fw-semibold">{{ $user->created_at->format('Y-m-d') }}</p>
                        <p class="text-muted mb-1">آخر تحديث:</p>
                        <p class="fw-semibold">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>

                    <div class="d-flex flex-column gap-2 mt-4">
                        @can('edit_users')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> تعديل البيانات
                            </a>
                            <form action="{{ route('users.toggle-status', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من تغيير حالة المستخدم؟');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning">
                                    <i class="fas fa-user-shield"></i> {{ $user->active ? 'إيقاف التفعيل' : 'تفعيل الحساب' }}
                                </button>
                            </form>
                            <form action="{{ route('users.reset-password', $user) }}" method="POST" onsubmit="return confirm('سيتم إنشاء كلمة مرور جديدة للمستخدم، هل تريد المتابعة؟');">
                                @csrf
                                <button type="submit" class="btn btn-outline-info">
                                    <i class="fas fa-key"></i> إعادة تعيين كلمة المرور
                                </button>
                            </form>
                        @endcan
                        @can('delete_users')
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i> حذف المستخدم
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
                    <h5 class="mb-0"><i class="fas fa-user-lock me-2"></i> الصلاحيات الرئيسية</h5>
                    <span class="badge bg-secondary-subtle text-secondary">{{ $rolePermissions->count() }} صلاحية</span>
                </div>
                <div class="card-body">
                    @if($rolePermissions->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-lock-open fa-2x mb-3"></i>
                            <p class="mb-0">لا توجد صلاحيات مرتبطة بهذا الدور.</p>
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
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> معلومات إضافية</h5>
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
                                <h6 class="text-muted">آخر تحديث للصلاحيات</h6>
                                <p class="fw-bold mb-0">{{ optional($user->roles->first()?->pivot)->created_at?->diffForHumans() ?? 'غير متوفر' }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="border rounded p-3">
                                <h6 class="text-muted">ملاحظات إدارية</h6>
                                <p class="mb-0 text-muted">يمكنك إضافة نظام للملاحظات المخصصة للمستخدمين في التحديثات القادمة.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
