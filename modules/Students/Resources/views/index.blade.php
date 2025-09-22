@extends('admin.layouts.master')

@section('title', 'إدارة الطلاب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة الطلاب')
    @section('page-subtitle', 'متابعة حسابات الطلاب وتحديث بياناتهم')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">الطلاب</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon students"><i class="fas fa-user-graduate"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">إجمالي الطلاب</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-success">{{ $stats['active'] }}</div>
                    <div class="stat-label">طلاب نشطون</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon teachers"><i class="fas fa-user-slash"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-danger">{{ $stats['inactive'] }}</div>
                    <div class="stat-label">طلاب غير نشطين</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i> تصفية النتائج</h5>
                @can('create_students')
                    <a href="{{ route('students.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> إضافة طالب جديد
                    </a>
                @endcan
            </div>

            <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label">بحث</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control" placeholder="ابحث باسم الطالب أو البريد أو اسم الدخول">
                </div>
                <div class="col-md-6 col-lg-4">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الحالة (الكل)</option>
                        <option value="active" @selected(request('status') === 'active')>نشط</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>غير نشط</option>
                    </select>
                </div>
                <div class="col-12 col-lg-4 d-flex align-items-end gap-2">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-rotate-left"></i> إعادة تعيين
                    </a>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> عرض النتائج
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>الطالب</th>
                            <th>البريد الإلكتروني</th>
                            <th>رقم الهاتف</th>
                            <th class="text-center">الحالة</th>
                            <th>آخر تسجيل دخول</th>
                            <th>تاريخ التسجيل</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $student->avatar_url }}" class="rounded-circle" width="42" height="42" alt="{{ $student->name }}">
                                        <div>
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <small class="text-muted" dir="ltr">{{ $student->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td dir="ltr">{{ $student->email ?? '—' }}</td>
                                <td dir="ltr">{{ $student->phone ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $student->active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $student->active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>{{ $student->last_login_at ? $student->last_login_at->diffForHumans() : 'لم يسجل بعد' }}</td>
                                <td>{{ optional($student->created_at)->format('Y-m-d') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view_students')
                                            <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('edit_students')
                                            <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('students.toggle-status', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="تغيير الحالة" onclick="return confirm('هل أنت متأكد من تغيير حالة الطالب؟');">
                                                    <i class="fas fa-user-shield"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('students.reset-password', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" title="إعادة تعيين كلمة المرور" onclick="return confirm('سيتم إنشاء كلمة مرور جديدة للطالب، هل تريد المتابعة؟');">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        @can('delete_students')
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا الطالب؟ لا يمكن التراجع عن هذا الإجراء.');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-graduate fa-2x mb-3"></i>
                                    <p class="mb-0">لا توجد بيانات طلاب لعرضها حالياً.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($students instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted">عرض {{ $students->count() }} من {{ $students->total() }} طالب</div>
                {{ $students->links() }}
            </div>
        @endif
    </div>
@endsection

