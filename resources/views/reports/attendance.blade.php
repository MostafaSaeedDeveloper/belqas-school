@extends('admin.layouts.master')

@section('title', 'تقارير الحضور التفصيلية - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تقارير الحضور')
    @section('page-subtitle', 'متابعة النسب اليومية والفترات الزمنية')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">التقارير</a></li>
        <li class="breadcrumb-item active">الحضور</li>
    @endsection
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.attendance') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">من تاريخ</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">إلى تاريخ</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-sync me-2"></i>تحديث</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">التوزيع حسب الحالة</h5>
                </div>
                <div class="card-body">
                    @if($byStatus->isEmpty())
                        <p class="text-muted mb-0">لا توجد بيانات في الفترة المحددة.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach(['present' => 'حضور', 'late' => 'تأخر', 'excused' => 'مُبرر', 'absent' => 'غياب'] as $status => $label)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $label }}</span>
                                    <span class="badge bg-secondary">{{ $byStatus[$status] ?? 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">نسبة الحضور اليومية</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>نسبة الحضور</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($byDay as $row)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row['date'])->translatedFormat('d/m/Y') }}</td>
                                    <td><span class="badge bg-success">{{ number_format($row['rate'], 1) }}%</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">لا توجد سجلات حضور في هذه الفترة.</td>
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
