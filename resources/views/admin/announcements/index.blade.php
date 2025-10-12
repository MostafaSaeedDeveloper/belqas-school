@extends('admin.layouts.master')

@section('title', 'الأخبار والإعلانات')

@section('page-header')
@section('page-title', 'الأخبار والإعلانات')
@section('page-subtitle', 'إدارة الرسائل والتنبيهات الموجهة للمجتمع المدرسي')
@section('breadcrumb')
    <li class="breadcrumb-item active">الأخبار والإعلانات</li>
@endsection
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="card-title mb-0"><i class="fas fa-bullhorn text-primary"></i> سجل الرسائل المنشورة</h3>
            <span class="text-muted small">يمكنك إضافة أخبار جديدة أو تعديل الإعلانات الحالية في أي وقت.</span>
        </div>
        <a href="{{ route('announcements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i>
            إضافة رسالة جديدة
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">العنوان</th>
                        <th scope="col" class="text-center">النوع</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">تاريخ النشر</th>
                        <th scope="col">تاريخ الانتهاء</th>
                        <th scope="col">آخر تحديث</th>
                        <th scope="col" class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr class="{{ $announcement->isExpired ? 'table-warning' : '' }}">
                            <td>
                                <div class="fw-semibold">{{ $announcement->title }}</div>
                                @if($announcement->summary)
                                    <div class="text-muted small">{{ \Illuminate\Support\Str::limit($announcement->summary, 120) }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $announcement->type === 'news' ? 'info' : 'secondary' }}">{{ $announcement->type === 'news' ? 'خبر' : 'إعلان' }}</span>
                            </td>
                            <td>
                                @if($announcement->is_published)
                                    <span class="badge bg-success">منشور</span>
                                @else
                                    <span class="badge bg-secondary">مسودة</span>
                                @endif
                            </td>
                            <td>
                                {{ optional($announcement->published_at)->translatedFormat('j F Y - h:i A') ?? '—' }}
                            </td>
                            <td>
                                {{ optional($announcement->expires_at)->translatedFormat('j F Y - h:i A') ?? '—' }}
                            </td>
                            <td>
                                {{ $announcement->updated_at?->diffForHumans() }}
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                        تعديل
                                    </a>
                                    <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-bell-slash fa-2x mb-3"></i>
                                    <p class="mb-1">لا توجد أخبار أو إعلانات بعد.</p>
                                    <p class="small">ابدأ بإضافة أول رسالة لمشاركة المستجدات مع الجميع.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($announcements->hasPages())
        <div class="card-footer d-flex justify-content-end">
            {{ $announcements->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
