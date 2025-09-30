@extends('admin.layouts.master')

@section('title', 'الطلاب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة الطلاب')
    @section('page-subtitle', 'متابعة بيانات الطلاب والسجلات الدراسية')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">الطلاب</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
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
        <div class="col-md-3">
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
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-slash"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-danger">{{ $stats['inactive'] }}</div>
                    <div class="stat-label">طلاب غير نشطين</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon classes"><i class="fas fa-people-group"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['with_guardian'] }}</div>
                    <div class="stat-label">طلاب محدثة بيانات ولي الأمر</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i> تصفية الطلاب</h5>
                <div class="d-flex gap-2">
                    @can('export_students')
                        <a href="{{ route('students.export', request()->query()) }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-export"></i> تصدير CSV
                        </a>
                    @endcan
                    @can('create_students')
                        <a href="{{ route('students.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> طالب جديد
                        </a>
                    @endcan
                </div>
            </div>

            <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">بحث</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control" placeholder="ابحث بالاسم أو البريد أو ولي الأمر">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label">الصف الدراسي</label>
                    <select name="grade_level" class="form-select">
                        <option value="">الكل</option>
                        @foreach($gradeOptions as $grade)
                            <option value="{{ $grade }}" @selected($filters['grade_level'] === $grade)>{{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label">الفصل</label>
                    <select name="classroom_id" class="form-select">
                        <option value="">الكل</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected($filters['classroom_id'] == $classroom->id)>
                                {{ $classroom->name }}
                                @if($classroom->grade_level)
                                    ({{ $classroom->grade_level }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label">الجنس</label>
                    <select name="gender" class="form-select">
                        <option value="">الكل</option>
                        <option value="male" @selected($filters['gender'] === 'male')>ذكر</option>
                        <option value="female" @selected($filters['gender'] === 'female')>أنثى</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="active" @selected(request('status') === 'active')>نشط</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>موقوف</option>
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
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
                            <th>الطالب</th>
                            <th>الصف / الفصل</th>
                            <th>ولي الأمر</th>
                            <th>رقم الهاتف</th>
                            <th>تاريخ الالتحاق</th>
                            <th class="text-center">الحالة</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php($profile = $student->studentProfile)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="rounded-circle" width="42" height="42">
                                        <div>
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <small class="text-muted" dir="ltr">{{ $student->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">{{ $profile?->grade_level ?? 'غير محدد' }}</span>
                                    <div class="text-muted small">{{ $profile?->classroom ? 'فصل ' . $profile->classroom : '' }}</div>
                                </td>
                                <td>
                                    <div>{{ $profile?->guardian_name ?? '—' }}</div>
                                    <div class="text-muted" dir="ltr">{{ $profile?->guardian_phone ?? '' }}</div>
                                </td>
                                <td dir="ltr">{{ $student->phone ?? '—' }}</td>
                                <td>{{ $profile?->enrollment_date?->translatedFormat('d F Y') ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $student->active ? 'bg-success' : 'bg-danger' }}">{{ $student->active ? 'نشط' : 'موقوف' }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view_students')
                                            <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('edit_students')
                                            <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_students')
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف الطالب؟');">
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
            {{ $students->withQueryString()->links() }}
        </div>
    </div>
@endsection
