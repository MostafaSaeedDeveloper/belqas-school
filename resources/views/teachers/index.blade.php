@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">المعلمون</h1>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary">إضافة معلم</a>
    </div>

    <form method="get" class="card card-body mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">بحث</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="اسم المعلم أو الكود">
            </div>
            <div class="col-md-4">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-outline-secondary">تطبيق</button>
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
                        <th>التخصص</th>
                        <th>الهاتف</th>
                        <th>الحالة</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->employee_code }}</td>
                            <td>
                                <a href="{{ route('teachers.show', $teacher) }}">{{ $teacher->name }}</a>
                                <div class="text-muted small">{{ $teacher->english_name }}</div>
                            </td>
                            <td>{{ $teacher->specialization }}</td>
                            <td>{{ $teacher->phone }}</td>
                            <td>{{ $teacher->status_label }}</td>
                            <td class="text-end">
                                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                <form method="post" action="{{ route('teachers.destroy', $teacher) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">لا توجد سجلات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection
