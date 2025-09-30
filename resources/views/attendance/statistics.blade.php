@extends('admin.layouts.master')

@section('title', 'إحصائيات الحضور - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'لوحة إحصائيات الحضور')
    @section('page-subtitle', 'مؤشرات تفاعلية لحالات الحضور والغياب عبر العام الدراسي')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">الإحصائيات</li>
    @endsection
@endsection

@php
    $terms = [
        ['name' => 'الفصل الأول', 'attendance' => 94, 'late' => 3, 'absent' => 3],
        ['name' => 'الفصل الثاني', 'attendance' => 91, 'late' => 5, 'absent' => 4],
    ];

    $monthlyStats = [
        ['month' => 'يناير', 'attendance' => 93, 'absent' => 4, 'late' => 3],
        ['month' => 'فبراير', 'attendance' => 90, 'absent' => 6, 'late' => 4],
        ['month' => 'مارس', 'attendance' => 92, 'absent' => 5, 'late' => 3],
        ['month' => 'أبريل', 'attendance' => 95, 'absent' => 3, 'late' => 2],
        ['month' => 'مايو', 'attendance' => 96, 'absent' => 2, 'late' => 2],
    ];

    $topPerformers = [
        ['classroom' => '1 / أ', 'attendance' => 97, 'late' => 1],
        ['classroom' => '2 / أ', 'attendance' => 95, 'late' => 2],
        ['classroom' => '3 / أ', 'attendance' => 99, 'late' => 1],
    ];

    $focusAreas = [
        ['title' => 'الصف الأول', 'issue' => 'تكرار الغياب أيام الأحد', 'action' => 'تنظيم لقاء مع أولياء الأمور'],
        ['title' => 'الصف الثالث', 'issue' => 'تأخر بداية الحصة الأولى', 'action' => 'تفعيل حوافز الالتزام المبكر'],
    ];
@endphp

@push('styles')
    <style>
        .statistics-card {
            border-radius: var(--border-radius-xl);
            border: 1px solid rgba(79, 93, 117, 0.1);
            box-shadow: 0 16px 40px rgba(30, 50, 100, 0.09);
            background: #fff;
            overflow: hidden;
        }

        .statistics-card .card-header {
            background: linear-gradient(120deg, rgba(63, 81, 181, 0.12), rgba(99, 102, 241, 0.15));
            border-bottom: none;
        }

        .chart-bar {
            height: 8px;
            border-radius: 50px;
            background: #edf2ff;
            overflow: hidden;
        }

        .chart-bar .fill {
            height: 100%;
            border-radius: inherit;
            transition: width 0.4s ease;
        }

        .chart-bar .fill.attendance {
            background: linear-gradient(90deg, #1f3c88, #4c6ef5);
        }

        .chart-bar .fill.absent {
            background: linear-gradient(90deg, #f03e3e, #f87171);
        }

        .chart-bar .fill.late {
            background: linear-gradient(90deg, #f59f00, #facc15);
        }

        .indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            color: #4f5d75;
        }

        .indicator::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .indicator.present::before { background: #1f3c88; }
        .indicator.absent::before { background: #f03e3e; }
        .indicator.late::before { background: #f59f00; }

        .sparkline {
            height: 60px;
            background: linear-gradient(180deg, rgba(79, 93, 117, 0.08), transparent 70%);
            border-radius: 18px;
            position: relative;
            overflow: hidden;
        }

        .sparkline::after {
            content: '';
            position: absolute;
            inset: 12px 16px;
            border: 1px dashed rgba(79, 93, 117, 0.2);
            border-radius: 14px;
        }

        .sparkline-chart {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding: 16px 24px 12px;
        }

        .sparkline-chart span {
            width: 12%;
            border-radius: 10px 10px 0 0;
            background: linear-gradient(180deg, rgba(31, 60, 136, 0.75), rgba(31, 60, 136, 0.35));
        }

        .focus-card {
            border-radius: 18px;
            border: 1px solid rgba(79, 93, 117, 0.15);
            padding: 18px;
            background: #fbfcff;
        }
    </style>
@endpush

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="statistics-card">
                <div class="card-header py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-line text-primary me-2"></i>نسب الحضور حسب الفصول الدراسية</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <span class="indicator present">حضور</span>
                        <span class="indicator late">تأخير</span>
                        <span class="indicator absent">غياب</span>
                    </div>
                    @foreach ($terms as $term)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">{{ $term['name'] }}</h6>
                                <span class="badge bg-primary-subtle text-primary">{{ $term['attendance'] }}% حضور</span>
                            </div>
                            <div class="chart-bar mb-2">
                                <div class="fill attendance" style="width: {{ $term['attendance'] }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>تأخير {{ $term['late'] }}%</span>
                                <span>غياب {{ $term['absent'] }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="statistics-card">
                <div class="card-header py-3">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt text-warning me-2"></i>الاتجاه الشهري</h5>
                </div>
                <div class="card-body">
                    <div class="sparkline mb-3">
                        <div class="sparkline-chart" id="monthlySparkline">
                            @foreach ($monthlyStats as $stat)
                                <span style="height: {{ max(20, $stat['attendance']) }}%"></span>
                            @endforeach
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الشهر</th>
                                    <th class="text-center">نسبة الحضور</th>
                                    <th class="text-center">الغياب</th>
                                    <th class="text-center">التأخير</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthlyStats as $stat)
                                    <tr>
                                        <td>{{ $stat['month'] }}</td>
                                        <td class="text-center"><span class="badge bg-success-subtle text-success">{{ $stat['attendance'] }}%</span></td>
                                        <td class="text-center"><span class="badge bg-danger-subtle text-danger">{{ $stat['absent'] }}%</span></td>
                                        <td class="text-center"><span class="badge bg-warning-subtle text-warning">{{ $stat['late'] }}%</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-5">
            <div class="statistics-card h-100">
                <div class="card-header py-3">
                    <h5 class="mb-0"><i class="fas fa-trophy text-success me-2"></i>أفضل الفصول التزاماً</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($topPerformers as $performer)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">فصل {{ $performer['classroom'] }}</h6>
                                    <small class="text-muted">برنامج التميز في الانضباط</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">{{ $performer['attendance'] }}% حضور</div>
                                    <small class="text-muted">تأخير {{ $performer['late'] }} حالات فقط</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="statistics-card h-100">
                <div class="card-header py-3">
                    <h5 class="mb-0"><i class="fas fa-bullseye text-danger me-2"></i>مجالات تحتاج متابعة</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($focusAreas as $area)
                            <div class="col-md-6">
                                <div class="focus-card h-100">
                                    <h6 class="mb-2 text-primary">{{ $area['title'] }}</h6>
                                    <p class="mb-2"><strong>ملاحظة:</strong> {{ $area['issue'] }}</p>
                                    <p class="mb-0 text-muted"><i class="fas fa-lightbulb me-2 text-warning"></i>{{ $area['action'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="statistics-card">
        <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="mb-1"><i class="fas fa-user-clock text-info me-2"></i>توزيع حالات التأخير حسب الوقت</h5>
                <p class="text-muted mb-0">التحليل يعرض فترات الذروة في وصول الطلاب خلال الأسبوع</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" data-range="week">أسبوعي</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-range="month">شهري</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="latenessTable">
                            <thead class="table-light">
                                <tr>
                                    <th>الفترة الزمنية</th>
                                    <th class="text-center">عدد الحالات</th>
                                    <th>نسبة التأثير</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>08:00 - 08:10 صباحاً</td>
                                    <td class="text-center"><span class="badge bg-warning-subtle text-warning">12</span></td>
                                    <td>
                                        <div class="chart-bar"><div class="fill late" style="width: 45%"></div></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>08:10 - 08:20 صباحاً</td>
                                    <td class="text-center"><span class="badge bg-warning-subtle text-warning">7</span></td>
                                    <td>
                                        <div class="chart-bar"><div class="fill late" style="width: 28%"></div></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>بعد 08:20 صباحاً</td>
                                    <td class="text-center"><span class="badge bg-danger-subtle text-danger">4</span></td>
                                    <td>
                                        <div class="chart-bar"><div class="fill absent" style="width: 18%"></div></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bg-light rounded-4 p-4 h-100">
                        <h6 class="mb-3">توصيات فورية</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <strong class="text-primary">08:00 - 08:10</strong>
                                <p class="mb-1 text-muted">تفعيل نظام الطابور الإلزامي لتعزيز الانضباط.</p>
                            </li>
                            <li class="mb-3">
                                <strong class="text-primary">08:10 - 08:20</strong>
                                <p class="mb-1 text-muted">إرسال تذكير مبكر لأولياء الأمور عبر التطبيق.</p>
                            </li>
                            <li>
                                <strong class="text-primary">بعد 08:20</strong>
                                <p class="mb-0 text-muted">إعداد تقرير أسبوعي خاص بالطلاب المتأخرين بشكل متكرر.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('inline-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rangeButtons = document.querySelectorAll('[data-range]');
            const latenessTable = document.getElementById('latenessTable');

            const dataset = {
                week: [
                    { label: '08:00 - 08:10 صباحاً', count: 12, percentage: 45 },
                    { label: '08:10 - 08:20 صباحاً', count: 7, percentage: 28 },
                    { label: 'بعد 08:20 صباحاً', count: 4, percentage: 18 },
                ],
                month: [
                    { label: '08:00 - 08:10 صباحاً', count: 42, percentage: 52 },
                    { label: '08:10 - 08:20 صباحاً', count: 31, percentage: 38 },
                    { label: 'بعد 08:20 صباحاً', count: 12, percentage: 24 },
                ],
            };

            const renderTable = (range) => {
                const rows = dataset[range] || dataset.week;
                const tbody = latenessTable?.querySelector('tbody');
                if (!tbody) return;

                tbody.innerHTML = rows.map(row => `
                    <tr>
                        <td>${row.label}</td>
                        <td class="text-center"><span class="badge bg-warning-subtle text-warning">${row.count}</span></td>
                        <td><div class="chart-bar"><div class="fill late" style="width: ${row.percentage}%"></div></div></td>
                    </tr>
                `).join('');
            };

            rangeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    rangeButtons.forEach(btn => btn.classList.remove('btn-primary'));
                    rangeButtons.forEach(btn => btn.classList.add('btn-outline-secondary'));
                    button.classList.remove('btn-outline-secondary');
                    button.classList.add('btn-primary');
                    renderTable(button.dataset.range);
                });
            });

            renderTable('week');
        });
    </script>
@endpush
