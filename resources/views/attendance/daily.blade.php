@extends('admin.layouts.master')

@section('title', 'إدارة الحضور اليومي - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الحضور والغياب')
    @section('page-subtitle', 'متابعة الحضور اليومي وتسجيل الحالات فوراً')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item active">الحضور اليومي</li>
    @endsection
@endsection

@php
    $today = now();
    $classrooms = [
        ['id' => 1, 'name' => '1 / أ', 'grade' => 'الصف الأول الابتدائي', 'teacher' => 'أ / هالة المصري'],
        ['id' => 2, 'name' => '1 / ب', 'grade' => 'الصف الأول الابتدائي', 'teacher' => 'أ / أحمد فوزي'],
        ['id' => 3, 'name' => '2 / أ', 'grade' => 'الصف الثاني الابتدائي', 'teacher' => 'أ / منى السعيد'],
        ['id' => 4, 'name' => '3 / ب', 'grade' => 'الصف الثالث الابتدائي', 'teacher' => 'أ / محمد العوضي'],
    ];

    $students = [
        ['id' => 101, 'name' => 'سارة محمد عبد الله', 'number' => 'ST-0001', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'present', 'note' => null],
        ['id' => 102, 'name' => 'أحمد خالد السعيد', 'number' => 'ST-0002', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'present', 'note' => null],
        ['id' => 103, 'name' => 'ياسمين علي إبراهيم', 'number' => 'ST-0003', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'late', 'note' => 'تأخر 10 دقائق'],
        ['id' => 104, 'name' => 'عبد الرحمن محمود', 'number' => 'ST-0004', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'present', 'note' => null],
        ['id' => 105, 'name' => 'مريم حسن فؤاد', 'number' => 'ST-0005', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'present', 'note' => null],
        ['id' => 106, 'name' => 'علي يوسف إبراهيم', 'number' => 'ST-0006', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'absent', 'note' => 'اتصال بولي الأمر'],
        ['id' => 107, 'name' => 'نورهان أحمد السيد', 'number' => 'ST-0007', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'present', 'note' => null],
        ['id' => 108, 'name' => 'مصطفى إبراهيم حلمي', 'number' => 'ST-0008', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'present', 'note' => null],
        ['id' => 109, 'name' => 'ملك محمد عبد العظيم', 'number' => 'ST-0009', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'excused', 'note' => 'عذر طبي معتمد'],
        ['id' => 110, 'name' => 'حمزة طارق عبد الحميد', 'number' => 'ST-0010', 'classroom' => '1 / أ', 'grade' => 'الصف الأول', 'status' => 'present', 'note' => null],
    ];

    $initialStats = [
        'present' => 0,
        'absent' => 0,
        'late' => 0,
        'excused' => 0,
    ];

    foreach ($students as $student) {
        if (isset($initialStats[$student['status']])) {
            $initialStats[$student['status']]++;
        }
    }
@endphp

@push('styles')
    <style>
        .attendance-stat-card {
            border-radius: var(--border-radius-xl);
            background: linear-gradient(135deg, rgba(36, 198, 220, 0.12), rgba(81, 74, 157, 0.08));
            border: 1px solid rgba(93, 135, 255, 0.15);
            box-shadow: 0 12px 30px rgba(23, 43, 77, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .attendance-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(23, 43, 77, 0.15);
        }

        .attendance-stat-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(93, 135, 255, 0.2), transparent 55%);
            opacity: 0.7;
        }

        .attendance-stat-card .card-body {
            position: relative;
            z-index: 1;
            padding: 22px 24px;
        }

        .attendance-stat-card .stat-label {
            font-size: 0.95rem;
            color: #3d5170;
        }

        .attendance-stat-card .stat-number {
            font-size: 2.25rem;
            font-weight: 700;
            color: #1f3c88;
        }

        .attendance-stat-card .stat-change {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .attendance-stat-card .icon-wrapper {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .attendance-actions .btn {
            min-width: 110px;
        }

        .attendance-table thead th {
            font-weight: 600;
            color: #4f5d75;
            background: #f8f9ff;
            border-bottom: 1px solid #e5e7f1;
        }

        .attendance-table tbody tr td {
            vertical-align: middle;
        }

        .attendance-toggle .btn {
            font-weight: 600;
            border-width: 2px;
        }

        .attendance-toggle .btn.active {
            box-shadow: 0 8px 18px rgba(76, 110, 245, 0.25);
        }

        .attendance-toggle .btn[data-status="present"] {
            border-color: #28a745;
            color: #1f7a2e;
            background-color: rgba(40, 167, 69, 0.08);
        }

        .attendance-toggle .btn[data-status="absent"] {
            border-color: #dc3545;
            color: #a71d2a;
            background-color: rgba(220, 53, 69, 0.08);
        }

        .attendance-toggle .btn[data-status="late"] {
            border-color: #ffc107;
            color: #b88600;
            background-color: rgba(255, 193, 7, 0.12);
        }

        .attendance-toggle .btn[data-status="excused"] {
            border-color: #17a2b8;
            color: #0f6674;
            background-color: rgba(23, 162, 184, 0.12);
        }

        .attendance-toggle .btn:not(.active) {
            opacity: 0.65;
        }

        .bulk-actions .btn {
            min-width: 160px;
        }

        .status-legend span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4f5d75;
        }

        .status-legend span::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .status-legend .legend-present::before {
            background-color: #28a745;
        }

        .status-legend .legend-absent::before {
            background-color: #dc3545;
        }

        .status-legend .legend-late::before {
            background-color: #ffc107;
        }

        .status-legend .legend-excused::before {
            background-color: #17a2b8;
        }

        .attendance-summary-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #e1e6f2;
            font-size: 0.95rem;
        }

        .attendance-summary-list li:last-child {
            border-bottom: none;
        }

        .attendance-summary-list .label {
            color: #4f5d75;
            font-weight: 600;
        }

        .attendance-summary-list .value {
            font-weight: 700;
            color: #1f3c88;
        }

        .attendance-summary-list .value small {
            font-size: 0.75rem;
            color: #8392a5;
            margin-right: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="attendance-stat-card shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-success-subtle text-success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <div class="stat-label">حاضرون اليوم</div>
                        <div class="stat-number" data-count="present">{{ $initialStats['present'] }}</div>
                        <div class="stat-change text-success"><i class="fas fa-arrow-up"></i> 3 طلاب أكثر من أمس</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="attendance-stat-card shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-danger-subtle text-danger">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div>
                        <div class="stat-label">غياب بدون عذر</div>
                        <div class="stat-number" data-count="absent">{{ $initialStats['absent'] }}</div>
                        <div class="stat-change text-danger"><i class="fas fa-arrow-down"></i> 1 أقل من المعدل</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="attendance-stat-card shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-warning-subtle text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="stat-label">متأخرون</div>
                        <div class="stat-number" data-count="late">{{ $initialStats['late'] }}</div>
                        <div class="stat-change text-warning"><i class="fas fa-equals"></i> نفس عدد الأمس</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="attendance-stat-card shadow-sm bg-white">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-info-subtle text-info">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <div>
                        <div class="stat-label">إذونات رسمية</div>
                        <div class="stat-number" data-count="excused">{{ $initialStats['excused'] }}</div>
                        <div class="stat-change text-info"><i class="fas fa-info-circle"></i> محدث منذ 15 دقيقة</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-xl-row justify-content-between gap-3 align-items-xl-center mb-4">
                <div>
                    <h4 class="mb-1">جلسة الحضور</h4>
                    <p class="text-muted mb-0">{{ $today->translatedFormat('l d F Y') }} &bull; آخر حفظ: <span id="attendance-last-save" class="fw-semibold">لم يتم الحفظ بعد</span></p>
                </div>
                <div class="d-flex flex-wrap gap-2 status-legend">
                    <span class="legend-present">حاضر</span>
                    <span class="legend-absent">غائب</span>
                    <span class="legend-late">متأخر</span>
                    <span class="legend-excused">مُعذَر</span>
                </div>
            </div>

            <form class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">تاريخ الحصة</label>
                    <input type="date" class="form-control" value="{{ $today->format('Y-m-d') }}">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">الصف الدراسي</label>
                    <select class="form-select">
                        <option value="">كل الصفوف</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom['grade'] }}">{{ $classroom['grade'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">الفصل</label>
                    <select class="form-select">
                        <option value="">كل الفصول</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom['id'] }}">{{ $classroom['name'] }} - {{ $classroom['teacher'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">بحث سريع</label>
                    <input type="text" class="form-control" id="attendance-search" placeholder="ابحث باسم الطالب أو رقمه">
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xxl-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                        <div>
                            <h5 class="mb-1">طلاب الفصل <span class="text-primary">1 / أ</span></h5>
                            <p class="text-muted mb-0">المعلم المسؤول: أ / هالة المصري &bull; بداية الحصة 08:00 ص</p>
                        </div>
                        <div class="bulk-actions d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-success btn-sm" data-bulk-action="present"><i class="fas fa-user-check me-1"></i> تحديد الكل حاضر</button>
                            <button type="button" class="btn btn-warning btn-sm" data-bulk-action="late"><i class="fas fa-clock me-1"></i> تحديد الكل متأخر</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bulk-action="reset"><i class="fas fa-rotate-left me-1"></i> إعادة التعيين</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table attendance-table align-middle mb-0" id="attendance-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>الطالب</th>
                                <th class="text-center">الصف / الفصل</th>
                                <th class="text-center">الحالة الحالية</th>
                                <th style="min-width: 260px;" class="text-center">تحديث الحالة</th>
                                <th style="min-width: 180px;">ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr class="attendance-row" data-student-id="{{ $student['id'] }}" data-status="{{ $student['status'] }}">
                                    <td class="fw-semibold">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar avatar-md rounded-circle bg-primary-subtle text-primary fw-bold">
                                                {{ mb_substr($student['name'], 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $student['name'] }}</div>
                                                <small class="text-muted" dir="ltr">{{ $student['number'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-semibold text-primary">{{ $student['classroom'] }}</div>
                                        <small class="text-muted">{{ $student['grade'] }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="attendance-status {{ 'attendance-' . $student['status'] }}" data-status-label>
                                            {{ ['present' => 'حاضر', 'absent' => 'غائب', 'late' => 'متأخر', 'excused' => 'مُعذَر'][$student['status']] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group attendance-toggle w-100" role="group">
                                            <button type="button" class="btn btn-outline-success btn-sm @if($student['status'] === 'present') active @endif" data-status="present">حاضر</button>
                                            <button type="button" class="btn btn-outline-danger btn-sm @if($student['status'] === 'absent') active @endif" data-status="absent">غائب</button>
                                            <button type="button" class="btn btn-outline-warning btn-sm @if($student['status'] === 'late') active @endif" data-status="late">متأخر</button>
                                            <button type="button" class="btn btn-outline-info btn-sm @if($student['status'] === 'excused') active @endif" data-status="excused">مُعذَر</button>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm attendance-note" value="{{ $student['note'] }}" placeholder="أضف ملاحظة...">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-primary" id="attendance-save">
                            <i class="fas fa-save me-2"></i> حفظ الحضور
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="attendance-export">
                            <i class="fas fa-file-export me-2"></i> تصدير التقرير
                        </button>
                    </div>
                    <div class="text-muted small">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        يتم إرسال تنبيه تلقائي لأولياء الأمور عند حفظ الغياب أو التأخير.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">ملخص اليوم</h5>
                    <ul class="attendance-summary-list mb-0">
                        <li>
                            <span class="label"><i class="fas fa-user-check text-success me-2"></i> حاضرون</span>
                            <span class="value" data-count="present">
                                {{ $initialStats['present'] }}
                                <small data-percentage="present"></small>
                            </span>
                        </li>
                        <li>
                            <span class="label"><i class="fas fa-user-times text-danger me-2"></i> غياب بدون عذر</span>
                            <span class="value" data-count="absent">
                                {{ $initialStats['absent'] }}
                                <small data-percentage="absent"></small>
                            </span>
                        </li>
                        <li>
                            <span class="label"><i class="fas fa-clock text-warning me-2"></i> متأخرون</span>
                            <span class="value" data-count="late">
                                {{ $initialStats['late'] }}
                                <small data-percentage="late"></small>
                            </span>
                        </li>
                        <li>
                            <span class="label"><i class="fas fa-file-medical text-info me-2"></i> بإذن رسمي</span>
                            <span class="value" data-count="excused">
                                {{ $initialStats['excused'] }}
                                <small data-percentage="excused"></small>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">إجراءات سريعة</h5>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-success" data-bulk-action="present">
                            <i class="fas fa-bell me-2"></i> إرسال تنبيه حضور للجميع
                        </button>
                        <button type="button" class="btn btn-outline-danger" data-bulk-action="notify-absent">
                            <i class="fas fa-sms me-2"></i> إخطار أولياء أمور الغائبين
                        </button>
                        <button type="button" class="btn btn-outline-warning" data-bulk-action="notify-late">
                            <i class="fas fa-envelope-open-text me-2"></i> تذكير المتأخرين
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bulk-action="print">
                            <i class="fas fa-print me-2"></i> طباعة كشف الحضور
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="toast align-items-center text-bg-success border-0" id="attendance-toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; bottom: 30px; left: 30px; z-index: 9999; display: none;">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i> تم حفظ الحضور بنجاح وإرسال الإشعارات.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"></button>
        </div>
    </div>
@endsection

@push('inline-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = Array.from(document.querySelectorAll('.attendance-row'));
            const statusMap = {
                present: { label: 'حاضر', className: 'attendance-present' },
                absent: { label: 'غائب', className: 'attendance-absent' },
                late: { label: 'متأخر', className: 'attendance-late' },
                excused: { label: 'مُعذَر', className: 'attendance-excused' },
            };

            const statsCounters = {
                present: document.querySelectorAll('[data-count="present"]'),
                absent: document.querySelectorAll('[data-count="absent"]'),
                late: document.querySelectorAll('[data-count="late"]'),
                excused: document.querySelectorAll('[data-count="excused"]'),
            };

            const percentageHolders = {
                present: document.querySelector('[data-percentage="present"]'),
                absent: document.querySelector('[data-percentage="absent"]'),
                late: document.querySelector('[data-percentage="late"]'),
                excused: document.querySelector('[data-percentage="excused"]'),
            };

            const searchInput = document.getElementById('attendance-search');
            const lastSaveElement = document.getElementById('attendance-last-save');
            const toastElement = document.getElementById('attendance-toast');
            const toastClose = toastElement?.querySelector('.btn-close');

            const recalcStats = () => {
                const totals = { present: 0, absent: 0, late: 0, excused: 0 };
                rows.forEach(row => {
                    const status = row.dataset.status;
                    if (totals[status] !== undefined) {
                        totals[status]++;
                    }
                });

                Object.entries(statsCounters).forEach(([status, elements]) => {
                    elements.forEach(element => {
                        element.textContent = totals[status];
                    });
                });

                const totalStudents = rows.length || 1;
                Object.entries(percentageHolders).forEach(([status, element]) => {
                    if (!element) return;
                    const percentage = Math.round((totals[status] / totalStudents) * 100);
                    element.textContent = `${percentage}%`;
                });
            };

            const updateRowStatus = (row, status) => {
                if (!statusMap[status]) return;
                row.dataset.status = status;

                const badge = row.querySelector('[data-status-label]');
                if (badge) {
                    badge.className = `attendance-status ${statusMap[status].className}`;
                    badge.textContent = statusMap[status].label;
                }

                row.querySelectorAll('.attendance-toggle .btn').forEach(button => {
                    button.classList.toggle('active', button.dataset.status === status);
                });

                recalcStats();
            };

            rows.forEach(row => {
                row.querySelectorAll('.attendance-toggle .btn').forEach(button => {
                    button.addEventListener('click', () => {
                        updateRowStatus(row, button.dataset.status);
                    });
                });
            });

            document.querySelectorAll('[data-bulk-action]').forEach(actionButton => {
                actionButton.addEventListener('click', () => {
                    const action = actionButton.dataset.bulkAction;

                    if (action === 'reset') {
                        rows.forEach(row => updateRowStatus(row, 'present'));
                        return;
                    }

                    if (['present', 'late', 'absent', 'excused'].includes(action)) {
                        rows.forEach(row => updateRowStatus(row, action));
                        return;
                    }

                    if (['notify-absent', 'notify-late', 'print'].includes(action)) {
                        showToast(`تم تنفيذ الإجراء: ${actionButton.textContent.trim()}`);
                    }
                });
            });

            const showToast = (message) => {
                if (!toastElement) return;
                toastElement.querySelector('.toast-body').textContent = message;
                toastElement.style.display = 'block';
                toastElement.classList.add('show');

                setTimeout(() => {
                    toastElement.classList.remove('show');
                    toastElement.style.display = 'none';
                }, 4000);
            };

            toastClose?.addEventListener('click', () => {
                toastElement.classList.remove('show');
                toastElement.style.display = 'none';
            });

            document.getElementById('attendance-save')?.addEventListener('click', () => {
                const now = new Date();
                const formatted = now.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
                if (lastSaveElement) {
                    lastSaveElement.textContent = `تم الحفظ منذ لحظات (${formatted})`;
                    lastSaveElement.classList.add('text-success');
                }
                showToast('تم حفظ الحضور بنجاح وإرسال الإشعارات.');
            });

            document.getElementById('attendance-export')?.addEventListener('click', () => {
                showToast('جاري تجهيز ملف CSV لتقارير الحضور.');
            });

            searchInput?.addEventListener('input', (event) => {
                const query = event.target.value.trim().toLowerCase();
                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2) .fw-semibold')?.textContent.toLowerCase() || '';
                    const code = row.querySelector('td:nth-child(2) small')?.textContent.toLowerCase() || '';
                    const matches = name.includes(query) || code.includes(query);
                    row.style.display = matches ? '' : 'none';
                });
            });

            recalcStats();
        });
    </script>
@endpush
