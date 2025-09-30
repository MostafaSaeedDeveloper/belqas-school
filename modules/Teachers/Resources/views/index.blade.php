@extends('admin.layouts.master')

@section('title', 'المعلمين - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة المعلمين')
    @section('page-subtitle', 'متابعة بيانات المعلمين وجداولهم')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">المعلمين</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon teachers"><i class="fas fa-chalkboard-teacher"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">إجمالي المعلمين</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-success">{{ $stats['active'] }}</div>
                    <div class="stat-label">معلمون نشطون</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-slash"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-danger">{{ $stats['inactive'] }}</div>
                    <div class="stat-label">معلمون موقوفون</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon classes"><i class="fas fa-book-open"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['with_subjects'] }}</div>
                    <div class="stat-label">معلمون محددة موادهم</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i> تصفية المعلمين</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('teachers.schedules') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-calendar-alt"></i> عرض الجداول
                    </a>
                    @can('create_teachers')
                        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> معلم جديد
                        </a>
                    @endcan
                </div>
            </div>

            <form method="GET" action="{{ route('teachers.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">بحث</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control" placeholder="ابحث بالاسم أو التخصص أو البريد">
                </div>
                <div class="col-md-3">
                    <label class="form-label">التخصص</label>
                    <select name="specialization" class="form-select">
                        <option value="">الكل</option>
                        @foreach($specializations as $specialization)
                            <option value="{{ $specialization }}" @selected($filters['specialization'] === $specialization)>{{ $specialization }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الجنس</label>
                    <select name="gender" class="form-select">
                        <option value="">الكل</option>
                        <option value="male" @selected($filters['gender'] === 'male')>ذكر</option>
                        <option value="female" @selected($filters['gender'] === 'female')>أنثى</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="active" @selected(request('status') === 'active')>نشط</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>موقوف</option>
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary">
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
                            <th>المعلم</th>
                            <th>التخصص</th>
                            <th>المواد الدراسية</th>
                            <th>الهاتف</th>
                            <th>تاريخ التعيين</th>
                            <th class="text-center">الحالة</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            @php($profile = $teacher->teacherProfile)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $teacher->avatar_url }}" alt="{{ $teacher->name }}" class="rounded-circle" width="42" height="42">
                                        <div>
                                            <div class="fw-semibold">{{ $teacher->name }}</div>
                                            <small class="text-muted" dir="ltr">{{ $teacher->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">{{ $profile?->specialization ?? 'غير محدد' }}</span>
                                    <div class="text-muted small">{{ $profile?->qualification ?? '—' }}</div>
                                </td>
                                <td>{{ $profile?->subjects_list ?? '—' }}</td>
                                <td dir="ltr">{{ $teacher->phone ?? '—' }}</td>
                                <td>{{ $profile?->formatted_hire_date ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $teacher->active ? 'bg-success' : 'bg-danger' }}">{{ $teacher->active ? 'نشط' : 'موقوف' }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view_teachers')
                                            <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('edit_teachers')
                                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_teachers')
                                            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف المعلم؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">لا توجد بيانات لعرضها.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            {{ $teachers->withQueryString()->links() }}
        </div>
    </div>
@endsection
