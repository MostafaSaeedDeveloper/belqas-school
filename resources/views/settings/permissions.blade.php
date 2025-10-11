@extends('admin.layouts.master')

@section('title', 'صلاحيات الوصول - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'إدارة الصلاحيات')
    @section('page-subtitle', 'تحديد أدوار المستخدمين وتوزيع الأذونات بدقة')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('settings.general') }}">الإعدادات</a></li>
        <li class="breadcrumb-item active">الصلاحيات</li>
    @endsection
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">الأدوار المتاحة</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($roles as $role)
                        <a href="{{ route('settings.permissions', ['role_id' => $role->id]) }}"
                           class="list-group-item list-group-item-action {{ optional($selectedRole)->id === $role->id ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $role->display_name ?? $role->name }}</span>
                                <span class="badge bg-light text-dark">{{ $role->permissions->count() }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">صلاحيات الدور</h5>
                        <small class="text-muted">{{ $selectedRole?->display_name ?? 'اختر دورًا من القائمة' }}</small>
                    </div>
                </div>
                <div class="card-body">
                    @if($selectedRole)
                        <form method="POST" action="{{ route('settings.permissions.update', $selectedRole) }}">
                            @csrf
                            <div class="row">
                                @foreach($permissions as $group => $items)
                                    <div class="col-md-6 mb-3">
                                        <h6 class="fw-bold text-primary">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $group)) }}</h6>
                                        <div class="form-check">
                                            @foreach($items as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="perm-{{ $permission->id }}"
                                                           name="permissions[]" value="{{ $permission->name }}"
                                                           {{ $selectedRole->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                           {{ $selectedRole->name === 'super_admin' ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                        {{ $permission->display_name ?? $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($selectedRole->name !== 'super_admin')
                                <div class="text-end">
                                    <button class="btn btn-primary" type="submit"><i class="fas fa-save me-2"></i>تحديث الصلاحيات</button>
                                </div>
                            @else
                                <div class="alert alert-info">لا يمكن تعديل صلاحيات المدير العام.</div>
                            @endif
                        </form>
                    @else
                        <p class="text-muted mb-0">يرجى اختيار دور لعرض الصلاحيات الخاصة به.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
