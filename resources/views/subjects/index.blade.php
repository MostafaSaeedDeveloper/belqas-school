@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">المواد الدراسية</h1>
        <a href="{{ route('subjects.create') }}" class="btn btn-primary">إضافة مادة</a>
    </div>

    <form method="get" class="card card-body mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">بحث</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="اسم المادة أو الكود">
            </div>
            <div class="col-md-4">
                <label class="form-label">المرحلة</label>
                <input type="text" name="grade_level" value="{{ $filters['grade_level'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100">تطبيق</button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>الاسم</th>
                        <th>المرحلة</th>
                        <th>المعلم</th>
                        <th>ساعات أسبوعية</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td>{{ $subject->code }}</td>
                            <td><a href="{{ route('subjects.show', $subject) }}">{{ $subject->name }}</a></td>
                            <td>{{ $subject->grade_level }}</td>
                            <td>{{ $subject->teacher?->name ?? '—' }}</td>
                            <td>{{ $subject->weekly_hours }}</td>
                            <td class="text-end">
                                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                <form method="post" action="{{ route('subjects.destroy', $subject) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">لا توجد مواد مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $subjects->links() }}
        </div>
    </div>
</div>
@endsection
