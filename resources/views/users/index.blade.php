@extends('admin.layouts.master')

@section('title', 'إدارة المستخدمين - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة المستخدمين')
    @section('page-subtitle', 'إدارة الحسابات والأدوار والصلاحيات')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">المستخدمون</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon students"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">إجمالي المستخدمين</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon teachers"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-success">{{ $stats['active'] }}</div>
                    <div class="stat-label">مستخدمون نشطون</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-slash"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-danger">{{ $stats['inactive'] }}</div>
                    <div class="stat-label">مستخدمون غير نشطين</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i> تصفية النتائج</h5>
                @can('create_users')
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> مستخدم جديد
                    </a>
                @endcan
            </div>

            <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">بحث</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control" placeholder="ابحث بالاسم أو البريد أو اسم الدخول">
                </div>
                <div class="col-md-4">
                    <label class="form-label">الدور</label>
                    <select name="role" class="form-select">
                        <option value="">جميع الأدوار</option>
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" @selected($filters['role'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الحالة (الكل)</option>
                        <option value="active" @selected(request('status') === 'active')>نشط</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>غير نشط</option>
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate-left"></i> إعادة تعيين
                    </a>
                    <button type="submit" class="btn btn-primary">
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
                            <th>المستخدم</th>
                            <th>الدور</th>
                            <th>البريد الإلكتروني</th>
                            <th>رقم الهاتف</th>
                            <th class="text-center">الحالة</th>
                            <th>آخر دخول</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $user->avatar_url }}" class="rounded-circle" width="42" height="42" alt="{{ $user->name }}">
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted" dir="ltr">{{ $user->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $user->roles->first()->display_name ?? $user->roles->first()->name ?? 'غير محدد' }}
                                    </span>
                                </td>
                                <td dir="ltr">{{ $user->email ?? '—' }}</td>
                                <td dir="ltr">{{ $user->phone ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $user->active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'لم يسجل بعد' }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view_users')
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('edit_users')
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="تغيير الحالة" onclick="return confirm('هل أنت متأكد من تغيير حالة المستخدم؟');">
                                                    <i class="fas fa-user-shield"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('users.reset-password', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" title="إعادة تعيين كلمة المرور" onclick="return confirm('سيتم إنشاء كلمة مرور جديدة للمستخدم، هل تريد المتابعة؟');">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        @can('delete_users')
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.');">
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
                                    <i class="fas fa-user-slash fa-2x mb-3"></i>
                                    <p class="mb-0">لا توجد بيانات لعرضها حالياً.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted">عرض {{ $users->count() }} من {{ $users->total() }} مستخدم</div>
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

@push('inline-scripts')
<script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(event) {
            const button = form.querySelector('button[type="submit"]');
            if (button && button.dataset.submitted) {
                event.preventDefault();
            } else if (button) {
                button.dataset.submitted = true;
            }
        });
    });
</script>
@endpush
