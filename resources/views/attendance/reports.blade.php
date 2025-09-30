@extends('admin.layouts.master')

@section('title', 'تقارير الحضور والغياب - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الحضور والغياب')
    @section('page-subtitle', 'تحليل شامل لنسب الحضور وتحركات الغياب عبر الفصول')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">التقارير</li>
    @endsection
@endsection

@php
    $summary = [
        'month' => 'مارس 2024',
        'attendance_rate' => 92,
        'absence_rate' => 5,
        'late_rate' => 3,
        'best_class' => '3 / أ',
        'best_class_rate' => 98,
        'improvement' => '+2.4%',
        'alerts_sent' => 34,
    ];

    $classesReport = [
        ['grade' => 'الأول الابتدائي', 'classroom' => '1 / أ', 'attendance' => 95, 'late' => 3, 'absent' => 2, 'trend' => '+1.5%'],
        ['grade' => 'الأول الابتدائي', 'classroom' => '1 / ب', 'attendance' => 90, 'late' => 6, 'absent' => 4, 'trend' => '-0.5%'],
        ['grade' => 'الثاني الابتدائي', 'classroom' => '2 / أ', 'attendance' => 93, 'late' => 4, 'absent' => 3, 'trend' => '+0.8%'],
        ['grade' => 'الثالث الابتدائي', 'classroom' => '3 / أ', 'attendance' => 98, 'late' => 1, 'absent' => 1, 'trend' => '+2.9%'],
        ['grade' => 'الثالث الابتدائي', 'classroom' => '3 / ب', 'attendance' => 87, 'late' => 8, 'absent' => 5, 'trend' => '-1.2%'],
    ];

    $studentsAbsence = [
        ['name' => 'ملك محمد عبد العظيم', 'classroom' => '1 / أ', 'absence' => 5, 'late' => 2, 'guardian' => 'محمد عبد العظيم'],
        ['name' => 'علي يوسف إبراهيم', 'classroom' => '1 / أ', 'absence' => 4, 'late' => 1, 'guardian' => 'يوسف إبراهيم'],
        ['name' => 'سارة أحمد فريد', 'classroom' => '2 / ب', 'absence' => 4, 'late' => 3, 'guardian' => 'أحمد فريد'],
        ['name' => 'مصطفى محمود عوض', 'classroom' => '3 / ب', 'absence' => 6, 'late' => 4, 'guardian' => 'محمود عوض'],
    ];

    $weeklyOverview = [
        ['day' => 'السبت', 'present' => 96, 'absent' => 3, 'late' => 1],
        ['day' => 'الأحد', 'present' => 92, 'absent' => 5, 'late' => 3],
        ['day' => 'الاثنين', 'present' => 94, 'absent' => 4, 'late' => 2],
        ['day' => 'الثلاثاء', 'present' => 90, 'absent' => 7, 'late' => 3],
        ['day' => 'الأربعاء', 'present' => 95, 'absent' => 3, 'late' => 2],
    ];
@endphp

@push('styles')
    <style>
        .report-card {
            border-radius: var(--border-radius-xl);
            border: 1px solid rgba(64, 81, 137, 0.08);
            box-shadow: 0 18px 40px rgba(31, 60, 136, 0.08);
            background: linear-gradient(160deg, rgba(79, 93, 117, 0.05), rgba(53, 83, 178, 0.12));
            color: #1f3c88;
        }

        .report-card .card-body {
            position: relative;
            padding: 22px 26px;
        }

        .report-card .floating-icon {
            position: absolute;
            inset-inline-end: 18px;
            inset-block-start: 18px;
            font-size: 2.2rem;
            opacity: 0.2;
        }

        .radial-progress {
            --size: 120px;
            width: var(--size);
            height: var(--size);
            border-radius: 50%;
            background: conic-gradient(#4460f7 calc(var(--percent) * 1%), #e2e6ff 0);
            display: grid;
            place-items: center;
            position: relative;
            margin: 0 auto;
        }

        .radial-progress::after {
            content: attr(data-label);
            width: calc(var(--size) - 26px);
            height: calc(var(--size) - 26px);
            border-radius: 50%;
            background: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 1.5rem;
            color: #1f3c88;
        }

        .trend-up {
            color: #1ea97c;
            font-weight: 700;
        }

        .trend-down {
            color: #e55353;
            font-weight: 700;
        }

        .reports-tabs .nav-link {
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            color: #4f5d75;
        }

        .reports-tabs .nav-link.active {
            background: #1f3c88;
            color: #fff;
            box-shadow: 0 12px 20px rgba(31, 60, 136, 0.2);
        }

        .attendance-heatmap {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
        }

        .attendance-heatmap .heatmap-card {
            border-radius: 18px;
            background: #fff;
            border: 1px solid #e5e9fa;
            padding: 14px;
            text-align: center;
        }

        .attendance-heatmap .heatmap-card strong {
            display: block;
            font-size: 1.4rem;
            color: #1f3c88;
        }

        .progress-label {
            font-weight: 600;
            color: #1f3c88;
        }

        .progress-percentage {
            font-weight: 700;
            color: #343a60;
        }

        .table-report thead th {
            font-weight: 700;
            background: #f0f4ff;
            color: #253165;
        }

        .badge-trend-up {
            background: rgba(30, 169, 124, 0.12);
            color: #11785b;
        }

        .badge-trend-down {
            background: rgba(229, 83, 83, 0.12);
            color: #a32020;
        }

        .guardian-name {
            font-size: 0.85rem;
            color: #7a8ba0;
        }

        .report-actions .btn {
            min-width: 150px;
        }
    </style>
@endpush

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="card report-card">
                <div class="card-body">
                    <div class="floating-icon"><i class="fas fa-chart-pie"></i></div>
                    <h6 class="text-uppercase text-muted mb-3">معدل الحضور العام</h6>
                    <div class="radial-progress" style="--percent: {{ $summary['attendance_rate'] }}" data-label="{{ $summary['attendance_rate'] }}%"></div>
                    <p class="mt-3 mb-1">أفضل فصل: <strong>{{ $summary['best_class'] }}</strong></p>
                    <p class="text-muted mb-0">نسبة التحسن الشهري <span class="trend-up">{{ $summary['improvement'] }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card report-card">
                <div class="card-body">
                    <div class="floating-icon"><i class="fas fa-bell"></i></div>
                    <h6 class="text-uppercase text-muted mb-3">تنبيهات أولياء الأمور</h6>
                    <h2 class="fw-bold mb-1">{{ $summary['alerts_sent'] }}</h2>
                    <p class="text-muted mb-2">تم إرسال رسائل نصية وتنبيهات تطبيق خلال الشهر الحالي</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-success-subtle text-success"><i class="fas fa-check-circle me-1"></i> نسبة التسليم 92%</span>
                        <button type="button" class="btn btn-outline-primary btn-sm">عرض سجل التنبيهات</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card report-card">
                <div class="card-body">
                    <div class="floating-icon"><i class="fas fa-calendar-check"></i></div>
                    <h6 class="text-uppercase text-muted mb-3">نظرة عامة شهرية</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between align-items-center py-2">
                            <span class="progress-label"><i class="fas fa-user-check text-success me-2"></i>الحضور</span>
                            <span class="progress-percentage">{{ $summary['attendance_rate'] }}%</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2">
                            <span class="progress-label"><i class="fas fa-user-times text-danger me-2"></i>الغياب</span>
                            <span class="progress-percentage">{{ $summary['absence_rate'] }}%</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center py-2">
                            <span class="progress-label"><i class="fas fa-clock text-warning me-2"></i>التأخير</span>
                            <span class="progress-percentage">{{ $summary['late_rate'] }}%</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-xl-row justify-content-between gap-3 align-items-xl-center mb-4">
                <div>
                    <h4 class="mb-1">إعدادات التقرير</h4>
                    <p class="text-muted mb-0">اختر الفترة الزمنية والفصول المطلوبة لتحليل الحضور</p>
                </div>
                <div class="report-actions d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-secondary"><i class="fas fa-file-pdf me-2"></i> تصدير PDF</button>
                    <button type="button" class="btn btn-outline-primary"><i class="fas fa-file-excel me-2"></i> تصدير Excel</button>
                    <button type="button" class="btn btn-primary"><i class="fas fa-share-alt me-2"></i> مشاركة التقرير</button>
                </div>
            </div>

            <form class="row g-3 mb-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">الفترة الزمنية</label>
                    <select class="form-select" id="report-period">
                        <option value="monthly" selected>تقرير شهري</option>
                        <option value="weekly">تقرير أسبوعي</option>
                        <option value="term">الفصل الدراسي الحالي</option>
                        <option value="custom">فترة مخصصة</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">الشهر</label>
                    <input type="month" class="form-control" value="2024-03">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">الصف الدراسي</label>
                    <select class="form-select">
                        <option value="">كل الصفوف</option>
                        <option value="grade-1">الصف الأول الابتدائي</option>
                        <option value="grade-2">الصف الثاني الابتدائي</option>
                        <option value="grade-3">الصف الثالث الابتدائي</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">نوع التقرير</label>
                    <select class="form-select">
                        <option value="summary">ملخص عام</option>
                        <option value="class">حسب الفصل</option>
                        <option value="student">حسب الطالب</option>
                        <option value="export">جاهز للطباعة</option>
                    </select>
                </div>
            </form>

            <ul class="nav nav-pills reports-tabs mb-3" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">نظرة عامة</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="classes-tab" data-bs-toggle="pill" data-bs-target="#classes" type="button" role="tab">حسب الفصول</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="students-tab" data-bs-toggle="pill" data-bs-target="#students" type="button" role="tab">الطلاب الأكثر غياباً</button>
                </li>
            </ul>

            <div class="tab-content" id="reportTabsContent">
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="attendance-heatmap mb-4">
                        @foreach ($weeklyOverview as $day)
                            <div class="heatmap-card">
                                <span class="text-muted small">{{ $day['day'] }}</span>
                                <strong>{{ $day['present'] }}%</strong>
                                <div class="d-flex justify-content-between small text-muted mt-2">
                                    <span><i class="fas fa-user-check text-success me-1"></i>{{ $day['present'] }}%</span>
                                    <span><i class="fas fa-user-times text-danger me-1"></i>{{ $day['absent'] }}%</span>
                                    <span><i class="fas fa-clock text-warning me-1"></i>{{ $day['late'] }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-muted mb-0">تركيز الحضور الأعلى كان في بداية الأسبوع مع انخفاض طفيف يوم الثلاثاء بسبب الظروف الجوية.</p>
                </div>
                <div class="tab-pane fade" id="classes" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-report align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>الصف</th>
                                    <th>الفصل</th>
                                    <th class="text-center">نسبة الحضور</th>
                                    <th class="text-center">نسبة التأخير</th>
                                    <th class="text-center">نسبة الغياب</th>
                                    <th class="text-center">الاتجاه</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classesReport as $class)
                                    <tr>
                                        <td>{{ $class['grade'] }}</td>
                                        <td>{{ $class['classroom'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success">{{ $class['attendance'] }}%</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-subtle text-warning">{{ $class['late'] }}%</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-danger">{{ $class['absent'] }}%</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ str_contains($class['trend'], '+') ? 'badge-trend-up' : 'badge-trend-down' }}">{{ $class['trend'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="students" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الطالب</th>
                                    <th>الفصل</th>
                                    <th class="text-center">أيام الغياب</th>
                                    <th class="text-center">أيام التأخير</th>
                                    <th>ولي الأمر</th>
                                    <th class="text-center">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentsAbsence as $student)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $student['name'] }}</div>
                                            <div class="text-muted small">تذكير أسبوعي بالإلتزام</div>
                                        </td>
                                        <td>{{ $student['classroom'] }}</td>
                                        <td class="text-center"><span class="badge bg-danger-subtle text-danger">{{ $student['absence'] }}</span></td>
                                        <td class="text-center"><span class="badge bg-warning-subtle text-warning">{{ $student['late'] }}</span></td>
                                        <td>
                                            <div>{{ $student['guardian'] }}</div>
                                            <div class="guardian-name" dir="ltr">01012345678</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary">اتصال فوري</button>
                                                <button type="button" class="btn btn-outline-secondary">خطة متابعة</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('inline-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const periodSelect = document.getElementById('report-period');
            const overviewTab = document.getElementById('overview-tab');

            periodSelect?.addEventListener('change', (event) => {
                const value = event.target.value;
                if (value === 'weekly') {
                    overviewTab?.click();
                }
            });
        });
    </script>
@endpush
