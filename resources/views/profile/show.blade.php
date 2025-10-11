@extends('admin.layouts.master')

@section('title', 'الملف الشخصي - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'ملفي الشخصي')
    @section('page-subtitle', 'استعراض معلومات الحساب وتاريخ النشاط')
    @section('breadcrumb')
        <li class="breadcrumb-item active">الملف الشخصي</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body">
                    <img src="{{ $user->avatar_url }}" alt="avatar" class="rounded-circle mb-3" width="120" height="120">
                    <h4 class="fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted mb-1">{{ $user->username }}</p>
                    <p class="text-muted">{{ $user->email ?? 'لا يوجد بريد مسجل' }}</p>
                    <div class="badge bg-primary mb-3">{{ $user->getRoleNames()->first() ?? 'بدون دور' }}</div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary"><i class="fas fa-user-edit me-2"></i>تعديل البيانات</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">بيانات الاتصال</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">الهاتف</dt>
                        <dd class="col-sm-9">{{ $user->phone ?? '—' }}</dd>
                        <dt class="col-sm-3">آخر تسجيل دخول</dt>
                        <dd class="col-sm-9">{{ optional($user->last_login_at)->diffForHumans() ?? 'غير متوفر' }}</dd>
                        <dt class="col-sm-3">العنوان</dt>
                        <dd class="col-sm-9">{{ optional($user->studentProfile)->address ?? optional($user->teacherProfile)->address ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">الصلاحيات المخولة</h5>
                </div>
                <div class="card-body">
                    @if($permissions->isEmpty())
                        <p class="text-muted mb-0">لا توجد صلاحيات خاصة لهذا الحساب.</p>
                    @else
                        <div class="row">
                            @foreach($permissions->chunk(ceil($permissions->count() / 2)) as $chunk)
                                <div class="col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($chunk as $name => $display)
                                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ $display }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
