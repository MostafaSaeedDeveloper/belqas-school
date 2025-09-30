@extends('admin.layouts.master')

@section('title', 'إدارة الطلاب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة الطلاب')
    @section('page-subtitle', 'متابعة سجلات الطلاب وتحديث بياناتهم')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">الطلاب</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon students"><i class="fas fa-user-graduate"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['total']) }}</div>
                    <div class="stat-label">إجمالي الطلاب</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon teachers"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-success">{{ number_format($stats['active']) }}</div>
                    <div class="stat-label">طلاب منتظمون</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-clock"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-warning">{{ number_format($stats['inactive']) }}</div>
                    <div class="stat-label">طلاب بحاجة للمتابعة</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon achievements"><i class="fas fa-venus-mars"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($stats['male']) }} / {{ number_format($stats['female']) }}</div>
                    <div class="stat-label">ذكور / إناث</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i> تصفية النتائج</h5>
                <div class="d-flex gap-2">
                    @can('view_students')
                        <a href="{{ route('students.reports') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-chart-pie"></i> تقارير الطلاب
                        </a>
                    @endcan
                    @can('create_students')
                        <a href="{{ route('students.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> إضافة طالب جديد
                        </a>
                    @endcan
                </div>
            </div>

            <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">بحث</label>
                    <input type="text" name="search" value="{{ $filters['search'] }}" class="form-control" placeholder="ابحث بالاسم أو الكود أو ولي الأمر">
                </div>
                <div class="col-md-2">
                    <label class="form-label">الصف الدراسي</label>
                    <select name="grade_level" class="form-select">
                        <option value="">الكل</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade }}" @selected($filters['grade_level'] === $grade)>{{ $grade }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">النوع</label>
                    <select name="gender" class="form-select">
                        <option value="">الكل</option>
                        @foreach($genderOptions as $value => $label)
                            <option value="{{ $value }}" @selected($filters['gender'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">عام القبول</label>
                    <select name="enrollment_year" class="form-select">
                        <option value="">الكل</option>
                        @foreach($enrollmentYears as $year)
                            <option value="{{ $year }}" @selected($filters['enrollment_year'] == $year)>{{ $year }}</option>
                        @endforeach
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
                            <th>الكود</th>
                            <th>الطالب</th>
                            <th>الصف / الفصل</th>
                            <th>ولي الأمر</th>
                            <th class="text-center">الحالة</th>
                            <th>الرصيد المستحق</th>
                            <th>تاريخ القبول</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td dir="ltr">{{ $student->student_code }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-placeholder bg-primary text-white">
                                            {{ $student->initials }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <small class="text-muted d-block">{{ $student->gender_label ?? 'غير محدد' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $student->grade_level ?? '—' }}</div>
                                    <small class="text-muted">{{ $student->classroom ?? '—' }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $student->guardian_name ?? '—' }}</div>
                                    <small class="text-muted" dir="ltr">{{ $student->guardian_phone ?? '—' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $student->status === 'active' ? 'success' : ($student->status === 'inactive' ? 'warning' : 'info') }}">{{ $student->status_label }}</span>
                                </td>
                                <td>{{ number_format($student->outstanding_fees, 2) }} ج.م</td>
                                <td>{{ $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '—' }}</td>
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
                                        @endcan
                                        @can('delete_students')
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirm('سيتم حذف سجل الطالب، هل أنت متأكد؟');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">لا توجد سجلات طلاب مطابقة لمعايير البحث الحالية.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $students->links() }}
        </div>
    </div>
@endsection
