@extends('admin.layouts.master')

@section('title', 'تقارير الطلاب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الطلاب')
    @section('page-subtitle', 'تحليل بيانات الطلاب وتوزيعهم على الصفوف والحالات المختلفة')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">الطلاب</a></li>
        <li class="breadcrumb-item active">التقارير</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon students"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($statistics['total']) }}</div>
                    <div class="stat-label">إجمالي الطلاب المسجلين</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon teachers"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-success">{{ number_format($statistics['active']) }}</div>
                    <div class="stat-label">طلاب نشطون</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-clock"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-warning">{{ number_format($statistics['inactive']) }}</div>
                    <div class="stat-label">طلاب تحت المراجعة</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon achievements"><i class="fas fa-venus-mars"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($statistics['male']) }} / {{ number_format($statistics['female']) }}</div>
                    <div class="stat-label">ذكور / إناث</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-school me-2"></i> توزيع الطلاب حسب الصفوف</h5>
                    <span class="badge bg-primary">{{ $gradeDistribution->sum('total') }} طالب</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الصف الدراسي</th>
                                    <th class="text-center">عدد الطلاب</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradeDistribution as $grade)
                                    <tr>
                                        <td>{{ $grade['grade_level'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ number_format($grade['total']) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i> توزيع الطلاب حسب الحالة</h5>
                    <span class="badge bg-primary">{{ $statusDistribution->sum('total') }} طالب</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الحالة</th>
                                    <th class="text-center">عدد الطلاب</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statusDistribution as $status)
                                    <tr>
                                        <td>{{ $status['label'] }}</td>
                                        <td class="text-center"><span class="badge bg-info">{{ number_format($status['total']) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-venus-mars me-2"></i> توزيع الطلاب حسب النوع</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>النوع</th>
                                    <th class="text-center">عدد الطلاب</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($genderDistribution as $gender)
                                    <tr>
                                        <td>{{ $gender['label'] }}</td>
                                        <td class="text-center"><span class="badge bg-warning text-dark">{{ number_format($gender['total']) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> أعلى أرصدة مستحقة</h5>
                    <span class="badge bg-danger">قيد المتابعة</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الطالب</th>
                                    <th>الصف</th>
                                    <th class="text-end">الرصيد (ج.م)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topOutstandingBalances as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->grade_level ?? '—' }}</td>
                                        <td class="text-end">{{ number_format($student->outstanding_fees, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">لا توجد بيانات مالية متاحة.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i> آخر الطلاب المسجلين</h5>
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-primary">عرض جميع الطلاب</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الطالب</th>
                                    <th>الكود</th>
                                    <th>الصف</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإضافة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentStudents as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td dir="ltr">{{ $student->student_code }}</td>
                                        <td>{{ $student->grade_level ?? '—' }}</td>
                                        <td><span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">{{ $student->status_label }}</span></td>
                                        <td>{{ $student->created_at?->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">لا توجد سجلات حديثة.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
