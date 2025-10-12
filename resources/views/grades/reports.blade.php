@extends('admin.layouts.master')

@section('title', 'تقارير الدرجات - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الدرجات')
    @section('page-subtitle', 'متابعة أداء الطلاب وتحليل التوزيع')
    @section('breadcrumb')
        <li class="breadcrumb-item">الدرجات</li>
        <li class="breadcrumb-item active">التقارير</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('grades.reports') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">الفصل الدراسي</label>
                    <select name="classroom_id" class="form-select">
                        <option value="">جميع الفصول</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ optional($selectedClassroom)->id === $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">المادة الدراسية</label>
                    <select name="subject_id" class="form-select">
                        <option value="">جميع المواد</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ optional($selectedSubject)->id === $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">نوع التقييم</label>
                    <select name="assessment_type" class="form-select">
                        <option value="">الكل</option>
                        @foreach(['exam' => 'امتحان نهائي', 'midterm' => 'اختبار نصفي', 'quiz' => 'اختبار قصير', 'assignment' => 'واجب'] as $value => $label)
                            <option value="{{ $value }}" {{ request('assessment_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">متوسط الدرجات</h6>
                    <div class="display-5 fw-bold text-primary">{{ $averageScore ? number_format($averageScore, 1) . '%' : '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">أعلى نتيجة</h6>
                    @if($highestScore)
                        <div class="fw-bold">{{ $highestScore->student->name }}</div>
                        <span class="badge bg-success">{{ number_format(($highestScore->score / $highestScore->max_score) * 100, 1) }}%</span>
                    @else
                        <p class="text-muted mb-0">—</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">أقل نتيجة</h6>
                    @if($lowestScore)
                        <div class="fw-bold">{{ $lowestScore->student->name }}</div>
                        <span class="badge bg-danger">{{ number_format(($lowestScore->score / $lowestScore->max_score) * 100, 1) }}%</span>
                    @else
                        <p class="text-muted mb-0">—</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">قائمة الدرجات</h5>
                    <span class="badge bg-light text-dark">{{ $records->total() }} سجل</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>المادة</th>
                                <th>الفصل</th>
                                <th>الدرجة</th>
                                <th>التقييم</th>
                                <th>تاريخ التسجيل</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($records as $record)
                                <tr>
                                    <td>{{ $record->student->name }}</td>
                                    <td>{{ $record->subject->name }}</td>
                                    <td>{{ $record->classroom->name }}</td>
                                    <td>{{ $record->score }} / {{ $record->max_score }}</td>
                                    <td><span class="badge bg-primary">{{ strtoupper($record->assessment_type) }}</span></td>
                                    <td>{{ $record->created_at->translatedFormat('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">لا توجد بيانات درجات متاحة.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    {{ $records->withQueryString()->links() }}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">توزيع التقديرات</h5>
                </div>
                <div class="card-body">
                    @if($distribution->isEmpty())
                        <p class="text-muted mb-0">لا توجد بيانات لعرضها.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($distribution as $grade => $total)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">{{ $grade ?: 'غير محدد' }}</span>
                                    <span class="badge bg-secondary">{{ $total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
