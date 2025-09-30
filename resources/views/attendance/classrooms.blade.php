@extends('admin.layouts.master')

@section('title', 'حضور الفصول - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'الحضور حسب الفصول')
    @section('page-subtitle', 'طريقة سريعة لتسجيل حضور كل فصل على حدة')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
        <li class="breadcrumb-item"><a href="{{ route('attendance.daily') }}">الحضور اليومي</a></li>
        <li class="breadcrumb-item active">حضور الفصول</li>
    @endsection
@endsection

@php
    $grades = [
        ['id' => 'g1', 'name' => 'المرحلة الابتدائية', 'levels' => ['الصف الأول', 'الصف الثاني', 'الصف الثالث']],
        ['id' => 'g2', 'name' => 'المرحلة الإعدادية', 'levels' => ['الصف الأول الإعدادي', 'الصف الثاني الإعدادي']],
    ];

    $classrooms = [
        [
            'id' => 1,
            'code' => 'CLS-101',
            'name' => '1 / أ',
            'grade' => 'الصف الأول',
            'teacher' => 'أ / هالة المصري',
            'present' => 24,
            'total' => 26,
            'late' => 1,
            'absent' => 1,
            'note' => 'تأخر ملحوظ لثلاثة طلاب منذ بداية الأسبوع',
            'students' => [
                ['id' => 101, 'name' => 'سارة محمد', 'status' => 'present'],
                ['id' => 102, 'name' => 'أحمد خالد', 'status' => 'present'],
                ['id' => 103, 'name' => 'ياسمين علي', 'status' => 'late'],
                ['id' => 104, 'name' => 'عبد الرحمن محمود', 'status' => 'present'],
                ['id' => 105, 'name' => 'مريم حسن', 'status' => 'absent'],
            ],
        ],
        [
            'id' => 2,
            'code' => 'CLS-203',
            'name' => '2 / ب',
            'grade' => 'الصف الثاني',
            'teacher' => 'أ / أحمد فوزي',
            'present' => 27,
            'total' => 27,
            'late' => 0,
            'absent' => 0,
            'note' => 'فصل منضبط – لا يوجد غياب مسجل هذا الأسبوع',
            'students' => [
                ['id' => 201, 'name' => 'ملك محمد', 'status' => 'present'],
                ['id' => 202, 'name' => 'مصطفى إبراهيم', 'status' => 'present'],
                ['id' => 203, 'name' => 'حسن عبد الله', 'status' => 'present'],
                ['id' => 204, 'name' => 'نورهان أحمد', 'status' => 'present'],
            ],
        ],
        [
            'id' => 3,
            'code' => 'CLS-305',
            'name' => '3 / ج',
            'grade' => 'الصف الثالث',
            'teacher' => 'أ / منى السعيد',
            'present' => 22,
            'total' => 25,
            'late' => 2,
            'absent' => 1,
            'note' => 'تم التواصل مع أولياء أمور المتغيبين',
            'students' => [
                ['id' => 301, 'name' => 'ليلى طارق', 'status' => 'present'],
                ['id' => 302, 'name' => 'مازن ياسر', 'status' => 'late'],
                ['id' => 303, 'name' => 'ريم خالد', 'status' => 'present'],
                ['id' => 304, 'name' => 'إياد محمود', 'status' => 'late'],
                ['id' => 305, 'name' => 'زياد محمد', 'status' => 'absent'],
            ],
        ],
    ];

    $statusLabels = [
        'present' => ['label' => 'حاضر', 'class' => 'success'],
        'late' => ['label' => 'متأخر', 'class' => 'warning'],
        'absent' => ['label' => 'غائب', 'class' => 'danger'],
    ];
@endphp

@push('styles')
    <style>
        .class-card {
            border-radius: var(--border-radius-xl);
            border: 1px solid rgba(31, 60, 136, 0.08);
            box-shadow: 0 16px 40px rgba(23, 43, 77, 0.08);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .class-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 60px rgba(23, 43, 77, 0.12);
        }

        .class-card-header {
            background: linear-gradient(130deg, rgba(31, 60, 136, 0.12), rgba(76, 110, 245, 0.1));
            padding: 1.5rem;
            position: relative;
        }

        .class-card-header::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(93, 135, 255, 0.25), transparent 55%);
        }

        .class-card-header > * {
            position: relative;
            z-index: 2;
        }

        .teacher-tag {
            border-radius: 999px;
            padding: 0.35rem 0.85rem;
            background: rgba(255, 255, 255, 0.65);
            border: 1px solid rgba(31, 60, 136, 0.18);
            font-weight: 600;
            color: #1f3c88;
        }

        .class-summary {
            display: flex;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .class-summary .summary-item {
            min-width: 110px;
        }

        .summary-item strong {
            font-size: 1.3rem;
            display: block;
            color: #1f3c88;
        }

        .class-card-body {
            padding: 1.5rem;
        }

        .student-row {
            border-radius: 14px;
            border: 1px solid rgba(31, 60, 136, 0.08);
            padding: 0.65rem 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.85rem;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .student-row:last-child {
            margin-bottom: 0;
        }

        .student-row[data-status="present"] {
            background: rgba(40, 167, 69, 0.08);
            border-color: rgba(40, 167, 69, 0.35);
        }

        .student-row[data-status="late"] {
            background: rgba(255, 193, 7, 0.12);
            border-color: rgba(255, 193, 7, 0.32);
        }

        .student-row[data-status="absent"] {
            background: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.35);
        }

        .student-controls .btn {
            min-width: 95px;
            font-weight: 600;
        }

        .student-controls .btn.active {
            box-shadow: 0 10px 28px rgba(25, 64, 155, 0.18);
        }

        .class-footer {
            background: #f8f9ff;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(31, 60, 136, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .bulk-btn {
            min-width: 130px;
            border-width: 2px;
        }

        .filters-wrapper {
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 12px 32px rgba(23, 43, 77, 0.08);
            padding: 1.5rem;
        }

        .filters-wrapper .nav-link {
            border-radius: 12px;
            padding: 0.6rem 1.25rem;
        }

        .filters-wrapper .nav-link.active {
            background: rgba(31, 60, 136, 0.1);
            color: #1f3c88;
            font-weight: 700;
        }

        .class-search input {
            border-radius: 14px;
            border: 1px solid rgba(31, 60, 136, 0.16);
        }
    </style>
@endpush

@section('content')
    <div class="row g-4 align-items-stretch">
        <div class="col-lg-4">
            <div class="filters-wrapper h-100">
                <h5 class="fw-bold mb-3 text-primary">إعدادات العرض السريع</h5>
                <p class="text-muted mb-4">اختر المرحلة والفصل للتنقل بين الفصول بسرعة.</p>

                <div class="mb-4 class-search">
                    <label for="class-search" class="form-label fw-semibold">البحث عن فصل</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa fa-search"></i></span>
                        <input type="search" id="class-search" class="form-control" placeholder="ابحث بالاسم أو الكود">
                    </div>
                </div>

                <ul class="nav nav-pills flex-column gap-2" role="tablist">
                    @foreach ($grades as $grade)
                        <li class="nav-item">
                            <button class="nav-link d-flex justify-content-between align-items-center {{ $loop->first ? 'active' : '' }}"
                                data-bs-toggle="pill" data-bs-target="#grade-{{ $grade['id'] }}" type="button" role="tab">
                                <span>
                                    <i class="fa fa-layer-group me-2 text-primary"></i>{{ $grade['name'] }}
                                </span>
                                <span class="badge bg-light text-primary border">{{ count($grade['levels']) }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-4">
                    @foreach ($grades as $grade)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="grade-{{ $grade['id'] }}">
                            <h6 class="fw-bold text-secondary mb-3">صفوف المرحلة</h6>
                            <ul class="list-unstyled mb-0">
                                @foreach ($grade['levels'] as $level)
                                    <li class="d-flex align-items-center justify-content-between py-2 px-3 mb-2 border rounded-3">
                                        <span><i class="fa fa-book-reader text-muted me-2"></i>{{ $level }}</span>
                                        <button class="btn btn-sm btn-outline-primary">عرض الفصول</button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold text-primary mb-1">فصول اليوم</h4>
                    <p class="text-muted mb-0">تابع حالة كل فصل وسجل الحضور مباشرة من نفس المكان.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary"><i class="fa fa-print me-2"></i>تقرير مطبوع</button>
                    <button class="btn btn-primary"><i class="fa fa-cloud-upload-alt me-2"></i>رفع ملف الحضور</button>
                </div>
            </div>

            <div class="d-flex flex-column gap-4">
                @foreach ($classrooms as $classroom)
                    <div class="class-card">
                        <div class="class-card-header text-white">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $classroom['name'] }}</h5>
                                    <p class="mb-1">{{ $classroom['grade'] }}</p>
                                    <span class="badge bg-light text-primary border">{{ $classroom['code'] }}</span>
                                </div>
                                <div class="teacher-tag">
                                    <i class="fa fa-chalkboard-teacher me-2"></i>{{ $classroom['teacher'] }}
                                </div>
                            </div>

                            <div class="class-summary mt-3">
                                <div class="summary-item">
                                    <span class="text-muted">الحضور</span>
                                    <strong>{{ $classroom['present'] }} / {{ $classroom['total'] }}</strong>
                                </div>
                                <div class="summary-item text-success">
                                    <span class="text-muted">حاضر</span>
                                    <strong>{{ $classroom['present'] }}</strong>
                                </div>
                                <div class="summary-item text-warning">
                                    <span class="text-muted">متأخر</span>
                                    <strong>{{ $classroom['late'] }}</strong>
                                </div>
                                <div class="summary-item text-danger">
                                    <span class="text-muted">غائب</span>
                                    <strong>{{ $classroom['absent'] }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="class-card-body">
                            <h6 class="fw-semibold text-primary mb-3">سجل طلاب الفصل</h6>
                            @foreach ($classroom['students'] as $student)
                                <div class="student-row" data-status="{{ $student['status'] }}">
                                    <div>
                                        <strong class="d-block">{{ $student['name'] }}</strong>
                                        <small class="text-muted">كود الطالب: ST-{{ $student['id'] }}</small>
                                    </div>
                                    <div class="student-controls btn-group" role="group">
                                        @foreach ($statusLabels as $statusKey => $status)
                                            <button class="btn btn-outline-{{ $status['class'] }} {{ $student['status'] === $statusKey ? 'active' : '' }}"
                                                data-status="{{ $statusKey }}">
                                                {{ $status['label'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="class-footer">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa fa-info-circle text-primary"></i>
                                <span class="text-muted">{{ $classroom['note'] }}</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-success bulk-btn">
                                    <i class="fa fa-check-double me-1"></i>
                                    الجميع حاضر
                                </button>
                                <button class="btn btn-outline-warning bulk-btn">
                                    <i class="fa fa-clock me-1"></i>
                                    تعليم المتأخرين
                                </button>
                                <button class="btn btn-primary bulk-btn">
                                    <i class="fa fa-save me-1"></i>
                                    حفظ تحديثات الفصل
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
