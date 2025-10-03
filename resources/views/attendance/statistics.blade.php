@extends('admin.layouts.master')

@section('title', 'إحصائيات الحضور - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إحصائيات الحضور')
    @section('page-subtitle', 'قراءة تحليلية لمؤشرات الحضور الشهرية')
    @section('breadcrumb')
        <li class="breadcrumb-item">الحضور</li>
        <li class="breadcrumb-item active">إحصائيات الحضور</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.statistics') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="month" class="form-label">الشهر</label>
                    <input type="month" id="month" name="month" value="{{ $selectedMonth->format('Y-m') }}" class="form-control">
                </div>
                <div class="col-md-8 d-flex justify-content-end gap-2">
                    <a href="{{ route('attendance.statistics') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate-left"></i> إعادة تعيين
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-chart-pie"></i> تحديث الإحصائيات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-calendar-check"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalSessions }}</div>
                    <div class="stat-label">حصص مسجلة</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $uniqueStudents }}</div>
                    <div class="stat-label">طلاب متابعون</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number text-success">{{ $statusTotals['present'] ?? 0 }}</div>
                    <div class="stat-label">مرات الحضور</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <div class="stat-card-header">
                    <div class="stat-icon attendance"><i class="fas fa-chart-line"></i></div>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $attendanceRate !== null ? $attendanceRate . '%' : '—' }}</div>
                    <div class="stat-label">متوسط الالتزام</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-signal"></i> توزيع الحالات</h5>
                </div>
                <div class="card-body">
                    @php($total = array_sum($statusTotals))
                    @if($total === 0)
                        <div class="empty-state">
                            <div class="empty-state-icon bg-light"><i class="fas fa-chart-pie"></i></div>
                            <h5 class="empty-state-title">لا توجد بيانات خلال هذا الشهر</h5>
                            <p class="empty-state-text">قم بتسجيل حضور الطلاب لعرض إحصائيات دقيقة.</p>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($statusTotals as $key => $value)
                                @php($percentage = $total > 0 ? round(($value / $total) * 100, 1) : 0)
                                <div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-semibold">{{ $key === 'present' ? 'حاضر' : ($key === 'absent' ? 'غائب' : ($key === 'late' ? 'متأخر' : 'مستأذن')) }}</span>
                                        <span class="text-muted small">{{ $value }} حالة ({{ $percentage }}%)</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chart-area"></i> الاتجاه اليومي</h5>
                </div>
                <div class="card-body">
                    @if($dailyTrends->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon bg-light"><i class="fas fa-chart-line"></i></div>
                            <h5 class="empty-state-title">لا توجد بيانات يومية</h5>
                            <p class="empty-state-text">سيتم عرض معدل الحضور اليومي بمجرد تسجيل الحصص.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>اليوم</th>
                                        <th class="text-center">الحضور</th>
                                        <th class="text-center">إجمالي الطلاب</th>
                                        <th class="text-center">نسبة الالتزام</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dailyTrends as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $item['date']->format('Y-m-d') }}</div>
                                                <div class="text-muted small">{{ $item['date']->translatedFormat('l') }}</div>
                                            </td>
                                            <td class="text-center">{{ $item['present'] }}</td>
                                            <td class="text-center">{{ $item['total'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary-subtle text-primary">{{ $item['rate'] }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-minus"></i> أعلى حالات الغياب</h5>
                </div>
                <div class="card-body">
                    @if($topAbsentees->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon bg-light"><i class="fas fa-user-shield"></i></div>
                            <h5 class="empty-state-title">لا يوجد غياب مسجل</h5>
                            <p class="empty-state-text">لم يتم تسجيل حالات غياب خلال الفترة المحددة.</p>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($topAbsentees as $row)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $row['student']?->name ?? 'طالب غير معروف' }}</div>
                                        <div class="text-muted small">{{ $row['student']?->studentProfile?->classroom ?? 'غير محدد' }}</div>
                                    </div>
                                    <span class="badge bg-danger-subtle text-danger fs-6">{{ $row['total'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-school"></i> أداء الفصول</h5>
                </div>
                <div class="card-body">
                    @if($classroomPerformance->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon bg-light"><i class="fas fa-school"></i></div>
                            <h5 class="empty-state-title">لا توجد بيانات للفصول</h5>
                            <p class="empty-state-text">سجلات الحضور ستظهر أفضل الفصول التزاماً بمجرد تسجيل البيانات.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>الفصل</th>
                                        <th class="text-center">نسبة الالتزام</th>
                                        <th class="text-center">عدد الحضور</th>
                                        <th class="text-center">إجمالي السجلات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classroomPerformance as $row)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $row['classroom']?->name ?? 'غير محدد' }}</div>
                                                <div class="text-muted small">{{ $row['classroom']?->grade_level }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success-subtle text-success">{{ $row['rate'] }}%</span>
                                            </td>
                                            <td class="text-center">{{ $row['present'] }}</td>
                                            <td class="text-center">{{ $row['total'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
